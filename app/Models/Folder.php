<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model {

    use HasFactory, SoftDeletes;

    protected $table = 'folders';

    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'folder_id',
        'name',
        'description',
        'password',
        'views',
        'space_disk',
    ];

    public function files() {
        return $this->hasMany(File::class);
    }

    public function accesses() {
        return $this->hasMany(Access::class, 'folder_id');
    }

    public function passwordRequere() {

        if ($this->user_id === auth()->id()) {
            return false;
        }

        if (($this->company_id === auth()->user()->company_id) && (auth()->user()->role == 'company')) {
            return false;
        }

        if (!is_null($this->password)) {
            return true;
        }

        return false;
    }

    public function iconLabel() {
        if($this->password != null) {
            return '<i class="ri-folder-lock-fill"></i>';
        }

        return '<i class="bi bi-folder-fill"></i>';
    }
}
