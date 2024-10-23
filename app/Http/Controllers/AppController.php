<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller {
    
    public function app(Request $request) {

        $query = Folder::whereNull('folder_id')->orderBy('name', 'desc');

        if(!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if (Auth::user()->role == 'company') {
            $query->where(function ($q) {
                $q->where('company_id', Auth::user()->id)
                  ->orWhere('user_id', Auth::user()->id);
            });
        } else {
            $query->where('user_id', Auth::user()->id);
        }

        $folders = $query->get();

        return view('app.app', [
            'title'   => 'Área de Trabalho',
            'folders' => $folders,
        ]);
    }

    public function shared(Request $request) {

        $query = Folder::orderBy('name', 'desc');

        if (!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->description)) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if (Auth::user()->role == 'company') {
            $query->where(function ($q) {
                $q->where('company_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id)
                ->orWhereHas('accesses', function ($subquery) {
                    $subquery->where('user_id', Auth::user()->id);
                });
            });
        } else {
            $query->whereHas('accesses', function ($subquery) {
                $subquery->where('user_id', Auth::user()->id);
            });
        }

        $folders = $query->get();

        return view('app.app', [
            'title'   => 'Área de Trabalho',
            'folders' => $folders,
        ]);
    }

    public function protecte(Request $request) {

        $query = Folder::whereNotNull('password')->orderBy('name', 'desc');

        if(!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if (Auth::user()->role == 'company') {
            $query->where(function ($q) {
                $q->where('company_id', Auth::user()->id)
                  ->orWhere('user_id', Auth::user()->id);
            });
        } else {
            $query->where('user_id', Auth::user()->id);
        }

        $folders = $query->get();

        return view('app.app', [
            'title'   => 'Área de Trabalho',
            'folders' => $folders,
        ]);
    }
}
