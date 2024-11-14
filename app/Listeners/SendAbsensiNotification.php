<?php

namespace App\Listeners;

use App\Events\AbsenGuruSaved;
use App\Models\User;
use App\Notifications\AbsensiNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAbsensiNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(AbsenGuruSaved $event)
    {
        // Ambil kelas yang terkait dari absen_guru
        $kelas_id = $event->absen_guru->kelas_id;

        // Temukan semua siswa di kelas yang bersangkutan
        $siswa = User::where('role', 'Sekretaris')->where('kelas_id', $kelas_id)->get();

        // Kirim notifikasi ke setiap siswa
        foreach ($siswa as $s) {
            $s->notify(new AbsensiNotification($event->absen_guru));
        }
    }
}
