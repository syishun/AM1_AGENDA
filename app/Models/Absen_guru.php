<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen_guru extends Model
{
    use HasFactory;

    protected $table = 'absen_gurus';

    public function dataGurus()
    {
        return $this->belongsToMany(Data_guru::class, 'guru_mapel', 'mapel_id', 'data_guru_id');
    }

    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel', 'data_guru_id', 'mapel_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
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
