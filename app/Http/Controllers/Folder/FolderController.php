<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;

use App\Models\Access;
use App\Models\File;
use App\Models\Folder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FolderController extends Controller {

    public function validatePassword(Request $request) {
        
        $folder = Folder::find($request->id);
        if ($folder && Hash::check($request->password, $folder->password)) {
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false], 403);
    }
    
    public function viewFolder(Request $request, $folder) {

        $folder = Folder::find($folder);
        if(!$folder) {
            return redirect()->back()->with('info', 'Não foi possível localizar dados da pasta!');
        } else {
            $folder->views += 1;
            $folder->save();
        }

        $folders = Folder::where('folder_id', $folder->id)->orderBy('name', 'asc');
        if(!empty($request->name)) {
            $folders->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $folders->where('description', 'like', '%' . $request->description . '%');
        }

        if(!empty($request->created_at)) {
            $folders->where('created_at',  $request->created_at);
        }

        $files = File::where('folder_id', $folder->id)->orderBy('name', 'asc');
        if(!empty($request->name)) {
            $files->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $files->where('description', 'like', '%' . $request->description . '%');
        }

        if(!empty($request->created_at)) {
            $files->where('created_at',  $request->created_at);
        }

        return view('app.Folder.view', [
            'folder'    => $folder,
            'folders'   => $folders->get(),
            'files'     => $files->get()
        ]);
    }

    public function editFolder($folder) {

        $folder = Folder::find($folder);
        if(!$folder) {
            return redirect()->back()->with('info', 'Não foi possível localizar dados da pasta!');
        }

        $usersFolder = Access::where('folder_id', $folder->id)->with('user')->get();

        return view('app.Folder.edit-folder', [
            'folder'        => $folder,
            'usersFolder'   => $usersFolder
        ]);
    }

    public function updateFolder(Request $request) {

        $folder = Folder::find($request->id);
        if(!$folder) {
            return redirect()->back()->with('info', 'Não foi possível localizar dados da pasta!');
        }

        if(!empty($request->name)) {
            $folder->name = $request->name;
        }

        if(!empty($request->description)) {
            $folder->description = $request->description;
        }

        if(!empty($request->password)) {
            $folder->password = $request->password;
        }

        if($folder->save()) {
            
            Access::where('folder_id', $folder->id)->delete();
            if ($request->has('user_access')) {
                foreach ($request->user_access as $userId) {
                    if ($userId) {
                        Access::create([
                            'user_id'    => $userId,
                            'folder_id'  => $folder->id,
                            'permission' => 1,
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Pasta atualizada com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível atualizar a pasta!');
    }

    public function createFolder(Request $request) {

        $folder              = new Folder();
        $folder->user_id     = Auth::user()->id;
        $folder->company_id  = Auth::user()->role == 'company' ? Auth::user()->id : Auth::user()->company_id;
        $folder->folder_id   = $request->folder_id;
        $folder->name        = $request->name;
        $folder->description = $request->description;
        if(!empty($request->password)) {
            $folder->password    = bcrypt($request->password);
        }
        if($folder->save()) {
            
            if ($request->has('user_access')) {
                foreach ($request->user_access as $userId) {
                    if($userId) {
                        Access::create([
                            'user_id'    => $userId,
                            'folder_id'  => $folder->id,
                            'permission' => 1,
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Pasta criada com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir o cadastro!');
    }

    public function deleteFolder(Request $request) {

        $folder = Folder::find($request->id);
        if($folder && $folder->delete()) {
            return response()->json(['message' => 'Pasta excluída com sucesso!'], 200);
        }

        return response()->json(['message' => 'Não foi possível excluir a Pasta!'], 400);
    }
}
