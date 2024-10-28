<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Absen_guru;

class AbsensiNotification extends Notification
{
    use Queueable;

    protected $absen_guru;

    public function __construct(Absen_guru $absen_guru)
    {
        $this->absen_guru = $absen_guru;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Absensi telah diperbarui oleh guru untuk kelas Anda pada tanggal " . $this->absen_guru->tgl,
            'absen_id' => $this->absen_guru->id,
        ];
    }
}
