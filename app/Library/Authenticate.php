<?php

namespace App\Library;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Authenticate {

    public function authGoogle($data) {
        
        $user = new User;
        $userFound = $user->where('email', $data->email)->first();
        if (!$userFound) {
            $userFound = User::create([
                'name'  => $data->givenName,
                'email' => $data->email,
                'photo' => $data->picture,
            ]);
        }

        Auth::login($userFound);

        return redirect()->to('/');
    }

    public function logout() {
        unset($_SESSION['user'], $_SESSION['logged']);
    }
}