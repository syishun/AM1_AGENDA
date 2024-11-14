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
        return $this->belongsTo('App\Models\Kelas', 'kelas_id', 'id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'id');
    }

    public function data_guru()
    {
        return $this->belongsTo(Data_guru::class, 'kode_guru', 'kode_guru');
    }
}
