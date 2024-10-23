<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\AssasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WalletController extends Controller {
    
    public function wallet(Request $request) {

        switch (env('APP_GATEWAY')) {
            case 'ASSAS':
                $gateway = new AssasController();

                $balance  = $gateway->balance();
                $extracts = $gateway->extract($request->startDate, $request->finishDate);
                break;
            default:
                $balance  = 0;
                $extracts = [];
                break;
        }

        return view('app.Finance.wallet', [
            'balance'  => $balance,
            'extracts' => $extracts
        ]);
    }

    public function withdrawSend(Request $request) {

        $password = $request->password;    
        if (Hash::check($password, auth()->user()->password)) {

            if(empty($request->key) || empty($request->value) || empty($request->type)) {
                return redirect()->back()->with('error', 'Dados incompletos!');
            }
    
            $gateway  = new AssasController();
            $withdraw = $gateway->withdrawSend($request->key, $this->formatarValor($request->value), $request->type);
    
            if($withdraw['success']) {
                return redirect()->back()->with('success', $withdraw['message']);
            }
    
            return redirect()->back()->with('error', 'Não foi possível realizar saque: '.$withdraw['message']);
        }

        return redirect()->back()->with('error', 'Senha inválida!');
    }

    private function formatarValor($valor) {
        
        $valor = preg_replace('/[^0-9,.]/', '', $valor);
        $valor = str_replace(['.', ','], '', $valor);

        return number_format(floatval($valor) / 100, 2, '.', '');
    }
}
