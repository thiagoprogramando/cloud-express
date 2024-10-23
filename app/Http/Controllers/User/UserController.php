<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\AssasController;

use App\Models\Plan;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller {

    public function profile() {
        
        return view('app.User.profile');
    }
    
    public function listUsers(Request $request, $role = null) {

        $query = User::orderBy('name', 'asc');

        if(!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->email)) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if(!empty($request->cpfcnpj)) {
            $query->where('cpfcnpj', 'like', '%' . $request->cpfcnpj . '%');
        }

        if(!empty($request->plans)) {
            $query->whereIn('plan_id', [$request->plans]);
        }

        if($role) {
            $query->where('role', $request->role);
        }

        if (Auth::user()->role == 'company') {
            $query->where('company_id', Auth::user()->id);
        } elseif(Auth::user()->role == 'admin') {
            $query->where('company_id', Auth::user()->company_id);
        }

        $users = $query->paginate(30);

        return view('app.User.list-users', [
            'users' => $users,
            'plans' => Plan::all()
        ]);
    }

    public function createUser(Request $request) {

        $validatedData = $request->validate([
            'cpfcnpj'   => 'required|string|unique:users,cpfcnpj|max:14',
            'email'     => 'required|email|max:255|unique:users,email',
        ], [
            'cpfcnpj.required'  => 'O campo CPF ou CNPJ é obrigatório.',
            'cpfcnpj.unique'    => 'Já existe um usuário com esse CPF ou CNPJ.',
            'cpfcnpj.max'       => 'O CPF ou CNPJ não pode ter mais que 14 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email'    => 'Por favor, insira um endereço de e-mail válido.',
            'email.unique'   => 'Este e-mail já está cadastrado.',
        ]);

        if (Auth::user()->type !== 1 && !$this->validateRegister(Auth::user()->id)) {
            return redirect()->back()->with('info', 'Você atingiu o limite máximo de usuários permitidos pelo seu plano atual!');
        }

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
        
        $user               = new User();
        $user->name         = $request->name;
        $user->phone        = preg_replace('/\D/', '', $request->phone);
        $user->email        = $request->email;
        $user->cpfcnpj      = preg_replace('/\D/', '', $request->cpfcnpj);
        $user->company_id   = $request->company_id;
        $user->plan_id      = $request->plan_id;
        $user->role         = $request->role;
        $user->password     = bcrypt(preg_replace('/\D/', '', $request->cpfcnpj));
        $user->customer     = $customer;
        if($user->save()) {
            return redirect()->back()->with('success', 'Perfil cadastrado com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir o cadastro!');
    }

    private function validateRegister($id) {

        $user = User::find($id);
        if(!$user) {
            return false;
        }

        $parent = $user->company_id ? $user->parent : $user;

        $totalUsers = $parent->usersCount();
        if ($totalUsers <= $parent->plan->space_user) {
            return true;
        }

        return false;
    }

    public function updateUser(Request $request) {

        $user = User::find($request->id);
        if(!$user) {
            return redirect()->back()->with('error', 'Ops! Não foram encontrado dados para o usuário.');
        }

        $data = [
            'name'      => $request->name,
            'cpfcnpj'   => $request->cpfcnpj,
            'email'     => $request->email,
        ];

        $data = array_filter($data, function($value) {
            return !empty($value);
        });

        if(!empty($request->photo) && $request->file('photo')->isValid()) {

            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $data['photo'] = 'storage/'.$path;
        }

        if(!empty($request->type)) {
            $data['type'] = $request->type;
        }

        if(!empty($request->password)) {
            if($request->password <> $request->confirmpassword) {
                return redirect()->back()->with('error', 'Senhas não coincidem!');
            }
            
            $data['password'] = bcrypt($request->type);
        }

        if($user && $user->update($data)) {
            return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível salvar às informações!');
    }

    public function deleteUser(Request $request) {

        $user = User::find($request->id);
        if ($user) {

            if ($user->photo) {
                Storage::delete($user->photo);
            }

            if($user->delete()) {
                return redirect()->back()->with('success', 'Perfil excluído com sucesso!');
            }

            return redirect()->back()->with('error', 'Não foi possível excluir o perfil!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir o perfil!');
    }
}
