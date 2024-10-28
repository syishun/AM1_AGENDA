<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_siswa extends Model
{
    use HasFactory;

    protected $table = 'data_siswas';

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'kelas_id', 'id');
    }
}
