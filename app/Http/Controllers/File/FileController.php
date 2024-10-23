<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;

use App\Library\GoogleClient;
use App\Models\File;
use App\Models\Folder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller {

    public function file($id, $download = null) {

        $file = File::find($id);
        if(!$file) {
            return redirect()->back()->with('error', 'Ops. Não foram encontrado dados do arquivo!');
        }

        $filePath = storage_path('app/public/' . $file->file);
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'O arquivo não existe no sistema.');
        }

        if($download <> null) {
            return response()->download($filePath, $file->name);
        }

        $fileUrl = asset('storage/' . $file->file);
        return response()->json(['url' => $fileUrl]);
    }

    public function editFile($folder) {

        $file = File::find($folder);
        if(!$file) {
            return redirect()->back()->with('info', 'Não foi possível localizar dados do arquivo!');
        }

        $folder = Folder::find($file->folder_id);
        return view('app.Folder.edit-file', [
            'file'     => $file,
            'folder'   => $folder,

        ]);
    }
    
    public function uploadFile(Request $request) {
        
        $acceptedFileTypes = ['jpeg', 'jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip'];
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            if (!in_array(strtolower($file->getClientOriginalExtension()), $acceptedFileTypes)) {
                return response()->json(['error' => 'Tipo de arquivo não aceito.'], 400);
            }
    
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('upload-files', $fileName);
    
            $fileRecord = File::create([
                'user_id'    => $request->user_id,
                'folder_id'  => $request->folder_id,
                'name'       => $file->getClientOriginalName(),
                'extension'  => $file->getClientOriginalExtension(),
                'space_disk' => $file->getSize(),
                'file'       => $path,
            ]);
    
            return response()->json([
                'id'        => $fileRecord->id,
                'name'      => $fileRecord->name,
                'iconLabel' => $fileRecord->iconLabel(),
            ], 200);
        }
    
        return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }

    public function updateFile(Request $request) {

        $file = File::find($request->id);
        if(!$file) {
            return redirect()->back()->with('info', 'Não foi possível localizar dados do arquivo!');
        }

        if(!empty($request->name)) {
            $file->name = $request->name;
        }

        if(!empty($request->description)) {
            $file->description = $request->description;
        }

        $acceptedFileTypes = ['jpeg', 'jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip'];
    
        if ($request->hasFile('file')) {
            $archive = $request->file('file');
    
            if (!in_array(strtolower($archive->getClientOriginalExtension()), $acceptedFileTypes)) {
                return redirect()->back()->with('info', 'Tipo de arquivo não aceito!');
            } else {
                $archiveDelete = $file->file;
            }
    
            $fileName = time() . '_' . uniqid() . '.' . $archive->getClientOriginalExtension();
            $path = $archive->storeAs('upload-files', $fileName);

            $file->extension = $file->getClientOriginalExtension();
            $file->space_disk = $file->getSize();
            $file->file = $path;

            if($file->save() && Storage::delete($archiveDelete)) {
                return redirect()->back()->with('success', 'Arquivo atualizado com sucesso!');
            }

            return redirect()->back()->with('error', 'Não foi possível atualizar os dados do arquivo!');
        }

        if($file->save()) {
            return redirect()->back()->with('success', 'Arquivo atualizado com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível atualizar os dados do arquivo!');
    }
    
    public function deleteFile(Request $request) {

        $file = File::find($request->id);
        if($file && $file->delete()) {
            
            if(!empty($request->desktop)) {
                return redirect()->route('app')->with('success', 'Arquivo excluído com sucesso!');
            }

            return response()->json(['message' => 'Arquivo excluído com sucesso!'], 200);
        }

        if(!empty($request->desktop)) {
            return redirect()->route('app')->with('success', 'Arquivo excluído com sucesso!');
        }
        return response()->json(['message' => 'Não foi possível excluir o arquivo!'], 400);
    }

    public function uploadToGoogleDrive(Request $request) {

        $file = File::find($request->id);
        if (!$file) {
            return response()->json(['message' => 'Arquivo não encontrado'], 404);
        }

        if (!in_array($file->extension, ['xls', 'xlsx', 'csv', 'docx', 'doc'])) {
            return response()->json(['message' => 'Apenas arquivos (xls, xlsx, csv, docx, doc) são permitidos!'], 401);
        } 

        $googleClient = new GoogleClient();
        $googleClient->init();

        if ($googleClient->authenticated()) {
            $uploadedFile = $file->uploadToGoogleDrive($googleClient);
            return response()->json(['message' => 'Arquivo enviado para o Google Drive', 'drive_file_id' => $uploadedFile->id], 200);
        } else {
            return redirect($googleClient->generateLink());
        }
    }
}
