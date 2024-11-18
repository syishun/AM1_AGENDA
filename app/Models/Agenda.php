<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas';

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'id');
    }

    public function dataGurus()
    {
        return $this->belongsToMany(Data_guru::class, 'guru_mapel', 'mapel_id', 'data_guru_id');
    }

    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel', 'data_guru_id', 'mapel_id');
    }

    public function data_guru()
    {
        return $this->belongsTo(Data_guru::class, 'kode_guru', 'kode_guru');
    }
}
