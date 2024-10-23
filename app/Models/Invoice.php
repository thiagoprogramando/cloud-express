<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

    use HasFactory;

    protected $table = "invoices";

    protected $fillable = [
        'user_id',
        'plan_id',
        'name',
        'description',
        'value',
        'due_date_payment',
        'token_payment',
        'url_payment',
        'status_payment'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    public function statusLabel() {
        switch ($this->status_payment) {
            case 1:
                return 'Aprovado';
                break;
            case 0:
                return 'Pendente';
                break;
            case 3:
                return 'Cancelado';
                break;
            default:
                return 'Pendente';
        }
    }
}
