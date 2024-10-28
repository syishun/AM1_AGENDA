<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapels';

    public function data_guru()
    {
        return $this->belongsTo('App\Models\Data_guru', 'kode_guru', 'id');
    }
}
