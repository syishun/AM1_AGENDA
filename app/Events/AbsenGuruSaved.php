<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Absen_guru;

class AbsenGuruSaved
{
    use Dispatchable, SerializesModels;

    public $absen_guru;

    public function __construct(Absen_guru $absen_guru)
    {
        $this->absen_guru = $absen_guru;
    }
}
