<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Plan;

class Month {

    public function handle(Request $request, Closure $next): Response {

        $user = Auth::user();
        $plan = $user->plan;

        if($user->role == 'user') {
            if ($user->parent) {
                $lastParentInvoice = $user->parent->invoices()->latest()->first();
                if ($lastParentInvoice && $lastParentInvoice->status_payment == 0) {
                    return redirect()->route('block')->with('info', 'Acesso limitado, consulte com o administrador!');
                }
            }
        } else {

            if ($plan && $plan->value > 1) {

                $lastInvoice = $user->invoices()->latest()->first();
                if($lastInvoice && $lastInvoice->status_payment == 0) {
                    return redirect()->route('invoices')->with('info', 'Existem faturas pendentes!');
                }
    
                switch ($plan->validate) {
                    case 'month':
                        if (!$lastInvoice || $lastInvoice->due_date_payment < Carbon::now()->subMonth()) {
                            $this->createInvoice($user, $plan);
                            return redirect()->route('invoices')->with('info', 'Existem faturas pendentes!');
                        }
                        break;
    
                    case 'year':
                        if (!$lastInvoice || $lastInvoice->due_date_payment < Carbon::now()->subYear()) {
                            $this->createInvoice($user, $plan);
                            return redirect()->route('invoices')->with('info', 'Existem faturas pendentes!');
                        }
                        break;
    
                    case 'lifetime':
                        
                        break;
                }
            }
        }

        return $next($request);
    }

    private function createInvoice($user, $plan) {
        
        $invoiceData = [
            'user_id'           => $user->id,
            'plan_id'           => $user->plan_id, 
            'name'              => 'Plano '.$plan->name,
            'description'       => 'Fatura do plano '.$plan->name,
            'value'             => $user->plan->value,
            'due_date_payment'  => Carbon::now()->addDays(3),
            'status_payment'    => 0, 
        ];

        return Invoice::create($invoiceData);
    }
}
