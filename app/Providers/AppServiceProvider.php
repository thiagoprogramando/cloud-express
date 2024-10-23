<?php

namespace App\Providers;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider {

    public function register(): void {
        
    }

    public function boot(): void {
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            
            if(Auth::check()) {

                if(Auth::user()->role == 'company') {
                    $users = User::where('company_id', Auth::user()->id)->get();
                } else {
                    $users = User::where('company_id', Auth::user()->company_id)->orWhere('id', Auth::user()->company_id)->get();
                }

                $protectFolders = Folder::where('user_id', Auth::user()->id)
                    ->whereNotNull('password')
                    ->get();

                $sharedFolders = Folder::whereHas('accesses', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })->get();

                
                $view->with([
                    'usersCompany'      => $users,
                    'protectFolders'    => $protectFolders,
                    'sharedFolders'     => $sharedFolders
                ]);
            }
        });
    }
}
