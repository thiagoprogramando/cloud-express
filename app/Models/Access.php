<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model {

    use HasFactory;

    protected $table = 'folder_accesses';

    protected $fillable = [
        'user_id',
        'folder_id',
        'permission',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function folder() {
        return $this->belongsTo(Folder::class, 'folder_id');
    }
}
