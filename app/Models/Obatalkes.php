<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obatalkes extends Model
{
    protected $table = 'obatalkes_m';
    protected $primaryKey = 'obatalkes_id';



    public function resep()
    {
        return $this->belongsToMany(Resep::class, 'resep_obat', 'obatalkes_id', 'resep_id');
    }
}