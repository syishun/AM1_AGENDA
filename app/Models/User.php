<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'password',
        'role',
        'kelas_id',
        'kode_guru',
    ];

    protected $hidden = [
        'password',
    ];
    // App\Models\User.php

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function data_guru()
    {
        return $this->belongsTo(Data_guru::class, 'kode_guru');
    }
}
