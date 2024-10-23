<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\AssasController;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller {
    
    public function plans(Request $request) {

        $query = Plan::orderBy('name', 'desc');

        if(!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if(!empty($request->description)) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if(!empty($request->min_value)) {
            $query->where('value', '>=', $request->min_value);
        }

        if(!empty($request->max_value)) {
            $query->where('value', '<=', $request->max_value);
        }

        $plans = $query->paginate(30);

        return view('app.Plan.list-plans', [
            'plans' => $plans
        ]);
    }

    public function payPlan(Request $request) {

        $plan = Plan::find($request->plan_id);
        if (!$plan) {
            return redirect()->route('plans')->with('error', 'Ops! Plano não encontrado!');
        }

        Invoice::where('status_payment', 0)->where('plan_id', $plan->id)->delete();

        $user = Auth::user();

        if (($plan->value <= 0) && ($plan->value < $user->plan->value)) {
            return redirect()->route('plans')->with('info', 'Para reduzir o seu plano, contate o suporte!');
        }

        if ($plan->value <= 0) {
            $user->plan_id = $plan->id;
            $user->save();
            
            return redirect()->route('plans')->with('success', 'Plano gratuito alterado com sucesso!');
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
            $charge = $gateway->createCharge(
                $user->customer,
                'PIX',
                $plan->value,
                'Contratação do Plano ' . $plan->name,
                now()->addDays(3)
            );
        }

        if (!isset($charge) || empty($charge['id']) || empty($charge['invoiceUrl'])) {
            return redirect()->back()->with('error', 'Ops! Não foi possível alterar o plano.');
        }

        $invoice                    = new Invoice();
        $invoice->name              = $plan->name;
        $invoice->description       = 'Contratação do Plano ' . $plan->name;
        $invoice->user_id           = $user->id;
        $invoice->plan_id           = $plan->id;
        $invoice->value             = $plan->value;
        $invoice->due_date_payment  = now()->addDays(3);
        $invoice->token_payment     = $charge['id'];
        $invoice->url_payment       = $charge['invoiceUrl'];
        $invoice->status_payment    = 0;

        if ($invoice->save()) {
            return redirect()->route('invoices')->with('success', 'Plano alterado. Confirme o pagamento!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível alterar o plano.');
    }

    public function createPlan(Request $request) {

        $plan               = new Plan();
        $plan->name         = $request->name;
        $plan->description  = $request->description;
        $plan->value        = $request->value;
        $plan->space_disk   = $request->space_disk;
        $plan->space_user   = $request->space_user;
        $plan->validate     = $request->validate;
        if($plan->save()) {
            return redirect()->back()->with('success', 'Plano cadastrado com sucesso!');
        }

        return redirect()->back()->with('error', 'Ops! Não foi possível concluir o cadastro!');
    }

    public function updateplan(Request $request) {

        $plan = Plan::find($request->id);
        if(!$plan) {
            return redirect()->back()->with('error', 'Ops! Não foram encontrado dados para o Plano.');
        }

        $data = [
            'name'          => $request->name,
            'description'   => $request->description,
            'value'         => $request->value,
            'space_disk'    => $request->space_disk,
            'space_user'    => $request->space_user,
            'validate'      => $request->validate
        ];

        $data = array_filter($data, function($value) {
            return !empty($value);
        });

        if($plan && $plan->update($data)) {
            return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível salvar às informações!');
    }

    public function deleteplan(Request $request) {

        $plan = plan::find($request->id);
        if ($plan && $plan->delete()) {

            return redirect()->back()->with('success', 'Plano excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir o Plano!');
    }

    public function plansPay() {

        $plans = Plan::all();
        return view('app.Plan.pay-plans', [
            'plans' => $plans
        ]);
    }

}
