<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;

// Halaman Admin
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Data_guruController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\Data_siswaController;
use App\Http\Controllers\UserController;

// Halaman Guru
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Absen_guruController;

// Halaman Siswa
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\Absen_siswaController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman Admin
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('admin', AdminController::class);
    Route::resource('data_guru', Data_guruController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('data_siswa', Data_siswaController::class);
    Route::resource('user', UserController::class);
    Route::get('/absen_siswa.admin_index', [Absen_siswaController::class, 'indexForAdmin'])->middleware('auth')->name('absen_siswa_admin');
    Route::get('/absen_siswa/admin_index', [Absen_siswaController::class, 'indexForAdmin'])->middleware('auth')->name('absen_siswa.admin_index');
});

// Halaman Guru
Route::middleware(['auth', 'role:Guru'])->group(function () {
    Route::resource('guru', GuruController::class);
    Route::get('agenda/create/{kelas_id}', [AgendaController::class, 'create']);
    Route::get('/agenda/{id}/edit', [AgendaController::class, 'edit']);
    Route::put('agenda/{id}', [AgendaController::class, 'update']);
    Route::get('absen_guru/create/{kelas_id}', [Absen_guruController::class, 'create']);
    Route::get('/absen_guru/{id}/edit', [Absen_guruController::class, 'edit']);
    Route::put('absen_guru/{id}', [Absen_guruController::class, 'update']);
});

// Halaman Siswa
Route::middleware(['auth', 'role:Perwakilan Kelas'])->group(function () {
    Route::resource('siswa', SiswaController::class);
    Route::resource('absen_siswa', Absen_siswaController::class);
});

Route::middleware(['auth', 'role:Guru,Admin'])->group(function () {
    Route::resource('agenda', AgendaController::class);
    Route::get('agenda/kelas/{id}', [AgendaController::class, 'agendaByClass']);
    Route::resource('absen_guru', Absen_guruController::class);
});

Route::middleware(['auth', 'role:Guru,Admin,Perwakilan Kelas'])->group(function () {
    Route::get('absen_guru/kelas/{id}', [Absen_guruController::class, 'absen_guruByClass']);
});

// Login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::delete('/deleteNotification/{id}', function ($id) {
    $notification = auth()->user()->notifications()->find($id);
    if ($notification) {
        $notification->delete();
    }
    return response()->json(['success' => true]);
});

use App\Http\Controllers\TestNotificationController;

Route::get('/test-notification', [TestNotificationController::class, 'sendTestNotification']);

Route::post('/send-notification', [NotificationController::class, 'sendNotification']);
