<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = false;
    protected $guarded = [];
    
    // Memberi tahu Laravel bahwa kolom password adalah 'password'
    public function getAuthPassword()
    {
        return $this->password;
    }
}
