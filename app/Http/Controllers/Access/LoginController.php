<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;

use App\Library\Authenticate;
use App\Library\GoogleClient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    
    public function login() {

        if(Auth::check()) {
            return redirect()->route('app');
        }

        $googleClient = new GoogleClient;
        $googleClient->init();

        if($googleClient->authenticated()) {
            $auth = new Authenticate();
            return $auth->authGoogle($googleClient->getData());
        }

        return view('login', [
            'authGoogle' => $googleClient->generateLink()
        ]);
    }

    public function logon(Request $request) {

        $credentials = $request->only(['email', 'password']);
        $credentials['password'] = $credentials['password'];
        if (Auth::attempt($credentials)) {
            
            return redirect()->route('app');
        } else {
            return redirect()->back()->with('error', 'Credenciais inválidas!');
        }
    }

    public function logout() {

        Auth::logout();
        return redirect()->route('login');
    }
}
