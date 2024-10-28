<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen_guru extends Model
{
    use HasFactory;

    protected $table = 'absen_gurus';

    // Model Mapel.php
    public function data_guru()
    {
        return $this->belongsTo(Data_guru::class, 'kode_guru', 'kode_guru');
    }

    public function mapel()
    {
        return $this->belongsTo('App\Models\Mapel', 'mapel_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'kelas_id', 'id');
    }

    protected $fillable = [
        'kode_guru',
        'mapel_id',
        'tgl',
        'kelas_id',
        'keterangan',
        'tugas'
    ];
}
