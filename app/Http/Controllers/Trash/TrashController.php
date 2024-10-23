<?php

namespace App\Http\Controllers\Trash;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller {
    
    public function trash(Request $request) {

        $folders = Folder::onlyTrashed()->orderBy('name', 'asc');
        if(!empty($request->name)) {
            $folders->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $folders->where('description', 'like', '%' . $request->description . '%');
        }

        if(!empty($request->deleted_at)) {
            $folders->where('deleted',  $request->deleted);
        }

        $files = File::onlyTrashed()->orderBy('name', 'asc');
        if(!empty($request->name)) {
            $files->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $files->where('description', 'like', '%' . $request->description . '%');
        }

        if(!empty($request->deleted)) {
            $files->where('deleted',  $request->deleted);
        }

        $users = User::onlyTrashed()->orderBy('name', 'asc');
        if(!empty($request->name)) {
            $users->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->deleted)) {
            $users->where('deleted',  $request->deleted);
        }

        return view('app.Trash.trash', [
            'folders' => $folders->where('user_id', Auth::user()->id)->get(),
            'files'   => $files->where('user_id', Auth::user()->id)->get(),
            'users'   => $users->where('company_id', Auth::user()->id)->get(),
        ]);
    }

    public function restoreFolder($id) {

        $folder = Folder::withTrashed()->find($id);
        if ($folder && $folder->trashed()) {

            $folder->restore();
            return redirect()->back()->with('success', 'Pasta restaurada com sucesso!');
        }

        return redirect()->back()->with('info', 'Não foi possível restaurar a Pasta!');
    }

    public function restoreFile($id) {

        $file = File::withTrashed()->find($id);
        if ($file && $file->trashed()) {

            $file->restore();
            return redirect()->back()->with('success', 'Arquivo restaurado com sucesso!');
        }

        return redirect()->back()->with('info', 'Não foi possível restaurar o Arquivo!');
    }

    public function restoreUser($id) {

        $user = User::withTrashed()->find($id);
        if ($user && $user->trashed()) {

            $user->restore();
            return redirect()->back()->with('success', 'Usuário restaurado com sucesso!');
        }

        return redirect()->back()->with('info', 'Não foi possível restaurar a Pasta!');
    }

    public function trashClear(Request $request) {

        $userId = $request->user_id;

        $folders = Folder::onlyTrashed()->where('user_id', $userId)->get();
        foreach ($folders as $folder) {
            
            $files = File::withTrashed()->where('folder_id', $folder->id)->get();
            foreach ($files as $file) {
                $file->forceDelete();
            }
    
            $folder->forceDelete();
        }

        $files = File::onlyTrashed()->where('user_id', $userId)->get();
        foreach ($files as $file) {
            $file->forceDelete();
        }

        $users = User::onlyTrashed()->where('company_id', $userId)->get();
        foreach ($users as $user) {
            $user->forceDelete();
        }
    
        return redirect()->back()->with('success', 'Lixeira limpa com sucesso!');
    }
}
