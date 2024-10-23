<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model {

    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'description',
        'space_disk',
        'space_user',
        'value',
        'validate',
    ];

    public function validateLabel() {
        switch ($this->validate) {
            case 'month':
                return 'Mês';
                break;
            case 'year':
                return 'Ano';
                break;
            case 'lifetime':
                return 'Vitalício';
                break;
            default:
                return '---';
        }
    }
}
