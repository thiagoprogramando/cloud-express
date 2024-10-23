<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller {
    
    public function invoices(Request $request) {

        $query = Invoice::orderBy('status_payment', 'asc');

        if(!empty($request->plan_id)) {
            $query->where('plan_id', $request->plan_id);
        }

        if(!empty($request->status_payment)) {
            $query->where('status_payment', $request->status_payment);
        }

        if(!empty($request->token_payment)) {
            $query->where('token_payment', $request->token_payment);
        }

        if(!empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        } else {
            $query->where('user_id', Auth::user()->id);
        }
        
        $invoices = $query->paginate(30);

        return view('app.Finance.list-invoice', [
            'invoices' => $invoices,
            'plans'    => Plan::all(),
        ]);

    }

    public function deleteInvoice(Request $request) {

        $invoice = Invoice::find($request->id);
        if($invoice && $invoice->delete()) {
            return redirect()->back()->with('success', 'Fatura excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir a Fatura!');
    }

    public function confirmInvoice($id) {

        $invoice = Invoice::find($id);
        if (!$invoice) {
            return redirect()->back()->with('error', 'Fatura não encontrada!');
        }

        $invoice->status_payment = 1;
        if (!$invoice->save()) {
            return redirect()->back()->with('error', 'Erro ao confirmar a fatura!');
        }

        $user = User::find($invoice->user_id);
        if (!$user) {
            return redirect()->back()->with('info', 'Fatura confirmada, mas nenhum usuário associado!');
        }

        $user->plan_id = $invoice->plan_id;
        if (!$user->save()) {
            return redirect()->back()->with('info', 'Fatura confirmada, mas o plano do usuário não foi alterado!');
        }

        User::where('company_id', $user->id)->update(['plan_id' => $invoice->plan_id]);
        return redirect()->back()->with('success', 'Fatura confirmada com sucesso!');
    }
}
