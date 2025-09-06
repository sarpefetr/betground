<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Urun extends Model
{
    protected $table = 'urunler';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'urun_adi',
        'urun_fiyati',
        'urun_resmi',
        'urun_kategorisi',
        'urun_markasi',
        'urun_aciklamasi'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'urun_kategorisi');
    }

    public function marka()
    {
        return $this->belongsTo(Marka::class, 'urun_markasi');
    }
}

