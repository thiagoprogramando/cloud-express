<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'plan_id',
        'company_id',
        'photo',
        'name',
        'cpfcnpj',
        'phone',
        'role',
        'type',
        'wallet',
        'email',
        'password',
        'customer',
    ];

    public function parent() {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function plan() {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    public function usersCount() {
        return User::where('company_id', $this->id)->count();
    }

    public function myUsers() {
       
        $user = Auth::user();

        if ($user->role === 'company') {
            return self::where('company_id', $user->id)->get();
        }

        if ($user->role === 'user') {
            return self::where('company_id', $user->company_id)
                        ->orWhere('id', $user->company_id)
                        ->get();
        }

        return collect();
    }

    public function typeLabel() {
        switch ($this->role) {
            case 'company':
                return 'Empresa';
                break;
            case 'admin':
                return 'Administrador';
                break;
            case 'user':
                return 'Colaborador';
                break;
            default:
                return '---';
        }
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
