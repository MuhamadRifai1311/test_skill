<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'reseps';
    protected $fillable = [
        'resep_tipe',
        'resep_tanggal',
    ];

    public function details()
    {
        return $this->hasMany(ResepDetail::class, 'resep_id');
    }
}
