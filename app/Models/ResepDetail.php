<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepDetail extends Model
{
    protected $table = 'resep_details';
    protected $fillable = [
        'nama_resep',
        'resep_id',
        'obatalkes_id',
        'signa_id',
        'jumlah',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'resep_id');
    }

    public function obatalkes()
    {
        return $this->belongsTo(Obatalkes::class, 'obatalkes_id');
    }

    public function signa()
    {
        return $this->belongsTo(Signa::class, 'signa_id');
    }
}
