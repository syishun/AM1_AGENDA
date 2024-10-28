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
    ];

    protected $hidden = [
        'password',
    ];
    // App\Models\User.php

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
