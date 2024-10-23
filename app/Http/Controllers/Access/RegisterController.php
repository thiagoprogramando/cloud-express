<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\AssasController;

use App\Models\User;

use App\Library\Authenticate;
use App\Library\GoogleClient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller {
    
    public function register($plan_id = null) {

        if(Auth::check()) {
            return redirect()->route('app');
        }

        $googleClient = new GoogleClient;
        $googleClient->init();

        if($googleClient->authenticated()) {
            $auth = new Authenticate();
            return $auth->authGoogle($googleClient->getData());
        }

        return view('register', [
            'plan_id'    => $plan_id,
            'authGoogle' => $googleClient->generateLink()
        ]);
    }

    public function createUser(Request $request) {

        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required',
            'cpfcnpj'   => 'required|string|unique:users,cpfcnpj|max:14',
            'email'     => 'required|email|max:255|unique:users,email',
            'password'  => 'required|string',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string'   => 'O campo nome deve ser um texto válido.',
            'name.max'      => 'O nome não pode ter mais que 255 caracteres.',

            'phone.required' => 'O campo telefone é obrigatório.',
            
            'cpfcnpj.required'  => 'O campo CPF ou CNPJ é obrigatório.',
            'cpfcnpj.unique'    => 'Já existe um usuário com esse CPF ou CNPJ.',
            'cpfcnpj.max'       => 'O CPF ou CNPJ não pode ter mais que 14 caracteres.',
            
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email'    => 'Por favor, insira um endereço de e-mail válido.',
            'email.unique'   => 'Este e-mail já está cadastrado.',
            
            'password.required'  => 'O campo senha é obrigatório.',
        ]);

        $gateway = null;

        switch (env('APP_GATEWAY')) {
            case 'ASSAS':
                $gateway = new AssasController();
                break;
            default:
                $gateway = new AssasController();
                break;
        }

        if ($gateway) {
            $customer = $gateway->createCustomer(
                $request->name,
                $request->cpfcnpj,
                $request->phone,
                $request->email
            );
        }

        if (!isset($customer) || empty($customer)) {
            return redirect()->back()->with('error', 'Ops, há algo de errado! Verifique seus dados e tente novamente!');
        }

        $user           = new User();
        $user->name     = $request->name;
        $user->cpfcnpj  = $request->cpfcnpj;
        $user->phone    = preg_replace('/\D/', '', $request->phone);
        $user->email    = $request->email;
        $user->password = bcrypt($request->password);
        $user->type     = 'user';
        $user->customer = $customer;

        if(!empty($request->plan_id)) {
            $user->plan_id = $request->plan_id;
        }

        $credentials = $request->only(['email', 'password']);
        if($user->save() && Auth::attempt($credentials)) {
            return redirect()->route('app');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível finalizar o cadastro, tente novamente mais tarde!');
    }
}
