<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// ──────────────────────────────────────────────────────────────────
// PUBLIC
// ──────────────────────────────────────────────────────────────────
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('landing');

Route::get('/login',    fn() => view('login'))->middleware('guest')->name('login');
Route::get('/register', fn() => view('register'))->middleware('guest')->name('register');

Route::post('/register-process', function (Request $request) {
    $request->validate([
        'name'     => 'required|string|max:255|min:2',
        'email'    => 'required|email:rfc,dns|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'email.unique'       => 'Email sudah terdaftar. Gunakan email lain atau login.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);
    User::create([
        'name'      => strip_tags(trim($request->name)),
        'email'     => strtolower(trim($request->email)),
        'password'  => Hash::make($request->password),
        'role'      => 'user',
        'is_active' => 1,
    ]);
    return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
})->name('register.process');

Route::post('/login-process', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);
    $credentials = ['email' => strtolower(trim($request->email)), 'password' => $request->password];
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        $user = auth()->user();
        $user->update(['last_login_at' => now()]);
        return redirect()->intended($user->role === 'admin' ? '/admin' : '/dashboard');
    }
    return back()->with('error', 'Email atau password salah.')->withInput($request->only('email'));
})->name('login.process');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get ('/auth/google',          [\App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('google.redirect');
Route::get ('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'callback'])->name('google.callback');

Route::get ('/cari-skor', fn() => view('cari-skor'))->name('public.cari-skor');
Route::post('/cari-skor', [\App\Http\Controllers\Admin\LaporanController::class, 'cariNomor'])->name('public.cari-skor.post');

// ──────────────────────────────────────────────────────────────────
// USER
// ──────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');

    // Materi
    Route::get('/materi',      [\App\Http\Controllers\User\MateriController::class, 'index'])->name('user.materi.index');
    Route::get('/materi/{id}', [\App\Http\Controllers\User\MateriController::class, 'show'])->name('user.materi.show');

    // Latihan
    Route::get ('/latihan',                   [\App\Http\Controllers\User\LatihanController::class, 'index'])->name('user.latihan.index');
    Route::get ('/latihan/{kategori}',         [\App\Http\Controllers\User\LatihanController::class, 'kerjakan'])->name('user.latihan.kerjakan');
    Route::post('/latihan/{kategori}/simpan',  [\App\Http\Controllers\User\LatihanController::class, 'simpanJawaban'])->name('user.latihan.simpan');
    Route::get ('/latihan/{kategori}/hasil',   [\App\Http\Controllers\User\LatihanController::class, 'hasil'])->name('user.latihan.hasil');
    Route::post('/latihan/{kategori}/reset',   [\App\Http\Controllers\User\LatihanController::class, 'reset'])->name('user.latihan.reset');

    // Tes Full
    Route::get ('/tes/full',              [\App\Http\Controllers\User\TesController::class, 'full'])->name('user.tes.full');
    Route::post('/tes/mulai',             [\App\Http\Controllers\User\TesController::class, 'mulai'])->name('user.tes.mulai');
    Route::get ('/tes/ujian',             [\App\Http\Controllers\User\TesController::class, 'ujian'])->name('user.tes.ujian');
    Route::post('/tes/submit',            [\App\Http\Controllers\User\TesController::class, 'submit'])->name('user.tes.submit');
    Route::post('/tes/catat-pelanggaran', [\App\Http\Controllers\User\TesController::class, 'catatPelanggaran'])->name('user.tes.pelanggaran');
    Route::post('/tes/autosave',          [\App\Http\Controllers\User\TesController::class, 'autosave'])->name('user.tes.autosave');
    Route::post('/tes/save-jawaban',      [\App\Http\Controllers\User\TesController::class, 'saveJawaban'])->name('user.tes.save-jawaban');

    // Tes Mini
    Route::get ('/tes/mini',        [\App\Http\Controllers\User\TesController::class, 'miniIndex'])->name('user.tes.mini');
    Route::post('/tes/mini/mulai',  [\App\Http\Controllers\User\TesController::class, 'miniMulai'])->name('user.tes.mini.mulai');
    Route::post('/tes/mini/submit', [\App\Http\Controllers\User\TesController::class, 'miniSubmit'])->name('user.tes.mini.submit');

    // Tes Simulasi
    Route::get ('/tes/simulasi',        [\App\Http\Controllers\User\TesController::class, 'simulasiIndex'])->name('user.tes.simulasi');
    Route::post('/tes/simulasi/mulai',  [\App\Http\Controllers\User\TesController::class, 'simulasiMulai'])->name('user.tes.simulasi.mulai');
    Route::get ('/tes/simulasi/ujian',  [\App\Http\Controllers\User\TesController::class, 'simulasiUjian'])->name('user.tes.simulasi.ujian');
    Route::post('/tes/simulasi/submit', [\App\Http\Controllers\User\TesController::class, 'simulasiSubmit'])->name('user.tes.simulasi.submit');

    // Pendaftaran
    Route::get ('/pendaftaran/status',     [\App\Http\Controllers\User\PendaftaranTesController::class, 'status'])->name('user.pendaftaran.status');
    Route::post('/tes/daftar',             [\App\Http\Controllers\User\PendaftaranTesController::class, 'daftar'])->name('user.pendaftaran.daftar');
    Route::post('/pendaftaran/{id}/batal', [\App\Http\Controllers\User\PendaftaranTesController::class, 'batal'])->name('user.pendaftaran.batal');

    // Hasil & Riwayat
    // Hasil, Riwayat, Grafik — 3 halaman BERBEDA (bukan sama)
    Route::get('/hasil',             [\App\Http\Controllers\User\HasilController::class, 'index'])->name('user.hasil.index');
    Route::get('/riwayat',           [\App\Http\Controllers\User\HasilController::class, 'riwayat'])->name('user.hasil.riwayat');
    Route::get('/grafik',            [\App\Http\Controllers\User\HasilController::class, 'grafik'])->name('user.hasil.grafik');
    Route::get('/hasil/{id}',        [\App\Http\Controllers\User\HasilController::class, 'detail'])->name('user.hasil.detail');
    Route::get('/hasil/{id}/cetak',  [\App\Http\Controllers\User\HasilController::class, 'cetak'])->name('user.hasil.cetak');

    // Profil
    Route::get('/profil', [\App\Http\Controllers\User\ProfilController::class, 'index'])->name('user.profil');
    Route::put('/profil', [\App\Http\Controllers\User\ProfilController::class, 'update'])->name('user.profil.update');

    // Notifikasi
    Route::get  ('/notifikasi',            [\App\Http\Controllers\User\NotifikasiController::class, 'index'])->name('user.notifikasi');
    Route::patch('/notifikasi/{id}/baca',  [\App\Http\Controllers\User\NotifikasiController::class, 'baca'])->name('user.notifikasi.baca');
    Route::patch('/notifikasi/baca-semua', [\App\Http\Controllers\User\NotifikasiController::class, 'bacaSemua'])->name('user.notifikasi.baca-semua');

    // FCM Push Token
    Route::post('/fcm/save-token', [\App\Http\Controllers\FcmController::class, 'saveToken'])->name('fcm.save-token');
});

// ──────────────────────────────────────────────────────────────────
// ADMIN
// ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // ── Paket Builder ──
    Route::prefix('paket-builder')->name('paket-builder.')->group(function() {
        Route::get ('/',                                       [\App\Http\Controllers\Admin\PaketBuilderController::class, 'index'])->name('index');
        Route::get ('/create',                                 [\App\Http\Controllers\Admin\PaketBuilderController::class, 'createPaket'])->name('create');
        Route::post('/',                                       [\App\Http\Controllers\Admin\PaketBuilderController::class, 'storePaket'])->name('store');
        Route::get ('/paket/{paketId}',                        [\App\Http\Controllers\Admin\PaketBuilderController::class, 'showPaket'])->name('paket');
        Route::post('/paket/{paketId}/selesaikan',             [\App\Http\Controllers\Admin\PaketBuilderController::class, 'selesaikanPaket'])->name('selesaikan');
        Route::post('/paket/{paketId}/grup',                   [\App\Http\Controllers\Admin\PaketBuilderController::class, 'storeGrup'])->name('grup.store');
        Route::get ('/paket/{paketId}/grup/{grupId}/modul/create',[\App\Http\Controllers\Admin\PaketBuilderController::class, 'createModul'])->name('modul.create');
        Route::post('/paket/{paketId}/grup/{grupId}/modul',    [\App\Http\Controllers\Admin\PaketBuilderController::class, 'storeModul'])->name('modul.store');
        Route::get ('/modul/{modulId}/input',                  [\App\Http\Controllers\Admin\PaketBuilderController::class, 'inputModul'])->name('modul.input');
        Route::delete('/modul/{modulId}',                      [\App\Http\Controllers\Admin\PaketBuilderController::class, 'destroyModul'])->name('modul.destroy');
        Route::post('/modul/{modulId}/passage',                [\App\Http\Controllers\Admin\PaketBuilderController::class, 'storePassage'])->name('modul.passage');
        Route::post('/modul/{modulId}/soal-passage',           [\App\Http\Controllers\Admin\PaketBuilderController::class, 'storeSoalPassage'])->name('modul.soal-passage');
        Route::post('/modul/{modulId}/missing-letters',        [\App\Http\Controllers\Admin\PaketBuilderController::class, 'storeMissingLetters'])->name('modul.missing-letters');
        Route::post('/modul/{modulId}/image-email',            [\App\Http\Controllers\Admin\PaketBuilderController::class, 'storeImageEmail'])->name('modul.image-email');
        Route::post('/modul/{modulId}/soal-listening',         [\App\Http\Controllers\Admin\PaketBuilderController::class, 'storeSoalListening'])->name('modul.soal-listening');
        Route::post('/modul/{modulId}/upload-gambar',          [\App\Http\Controllers\Admin\PaketBuilderController::class, 'uploadGambar'])->name('modul.upload-gambar');
        Route::get ('/paket/{paketId}/preview',[\App\Http\Controllers\Admin\PaketBuilderController::class, 'previewPaket'])->name('preview');
        Route::delete('/soal/{soalId}',                        [\App\Http\Controllers\Admin\PaketBuilderController::class, 'destroySoal'])->name('soal.destroy');
    });

    // ── Reading Builder ──
    Route::prefix('reading-builder')->name('reading-builder.')->group(function() {
        Route::get ('/',                                [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'index'])->name('index');
        Route::get ('/paket/{paketId}',                [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'showPaket'])->name('paket');
        Route::post('/paket/{paketId}/selesaikan',     [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'selesaikanPaket'])->name('selesaikan');
        Route::get ('/paket/{paketId}/modul/create',   [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'createModul'])->name('modul.create');
        Route::post('/paket/{paketId}/modul',          [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'storeModul'])->name('modul.store');
        Route::get ('/modul/{modulId}/input',          [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'inputModul'])->name('modul.input');
        Route::delete('/modul/{modulId}',              [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'destroyModul'])->name('modul.destroy');
        Route::post('/modul/{modulId}/passage',        [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'storePassage'])->name('modul.passage');
        Route::post('/modul/{modulId}/soal-passage',   [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'storeSoalPassage'])->name('modul.soal-passage');
        Route::post('/modul/{modulId}/missing-letters',[\App\Http\Controllers\Admin\ReadingBuilderController::class, 'storeMissingLetters'])->name('modul.missing-letters');
        Route::post('/modul/{modulId}/image-email',    [\App\Http\Controllers\Admin\ReadingBuilderController::class, 'storeImageEmail'])->name('modul.image-email');
    });

    // ── Listening Audio Paket ──
    Route::get   ('listening',               [\App\Http\Controllers\Admin\ListeningController::class, 'index'])->name('listening.index');
    Route::get   ('listening/create',        [\App\Http\Controllers\Admin\ListeningController::class, 'create'])->name('listening.create');
    Route::post  ('listening',               [\App\Http\Controllers\Admin\ListeningController::class, 'store'])->name('listening.store');
    Route::get   ('listening/{id}',          [\App\Http\Controllers\Admin\ListeningController::class, 'show'])->name('listening.show');
    Route::post  ('listening/{id}/soal',     [\App\Http\Controllers\Admin\ListeningController::class, 'storeSoal'])->name('listening.storeSoal');
    Route::post  ('listening/{id}/durasi',   [\App\Http\Controllers\Admin\ListeningController::class, 'updateDurasi'])->name('listening.updateDurasi');
    Route::delete('listening/soal/{soalId}', [\App\Http\Controllers\Admin\ListeningController::class, 'destroySoal'])->name('listening.destroySoal');
    Route::delete('listening/{id}',          [\App\Http\Controllers\Admin\ListeningController::class, 'destroy'])->name('listening.destroy');

    // Bank Soal
    Route::get   ('soal',              [\App\Http\Controllers\Admin\BankSoalController::class, 'index'])->name('soal.index');
    Route::get   ('soal/group',         function() { return view('admin.soal.group'); })->name('soal.group');
    Route::get   ('soal/modul-list',    function() { return view('admin.soal.modul'); })->name('soal.modul');
    Route::get   ('soal/create',       [\App\Http\Controllers\Admin\BankSoalController::class, 'create'])->name('soal.create');
    Route::post  ('soal',              [\App\Http\Controllers\Admin\BankSoalController::class, 'store'])->name('soal.store');
    Route::get   ('soal/{id}/edit',    [\App\Http\Controllers\Admin\BankSoalController::class, 'edit'])->name('soal.edit');
    Route::put   ('soal/{id}',         [\App\Http\Controllers\Admin\BankSoalController::class, 'update'])->name('soal.update');
    Route::delete('soal/{id}',         [\App\Http\Controllers\Admin\BankSoalController::class, 'destroy'])->name('soal.destroy');
    Route::patch ('soal/{id}/toggle',  [\App\Http\Controllers\Admin\BankSoalController::class, 'toggleAktif'])->name('soal.toggle');
    Route::get   ('soal/{id}/preview', [\App\Http\Controllers\Admin\BankSoalController::class, 'preview'])->name('soal.preview');

    // Grup Soal
    Route::get   ('grup-soal',           [\App\Http\Controllers\Admin\GrupSoalController::class, 'index'])->name('grup.index');
    Route::get   ('grup-soal/create',    [\App\Http\Controllers\Admin\GrupSoalController::class, 'create'])->name('grup.create');
    Route::post  ('grup-soal',           [\App\Http\Controllers\Admin\GrupSoalController::class, 'store'])->name('grup.store');
    Route::get   ('grup-soal/{id}',      [\App\Http\Controllers\Admin\GrupSoalController::class, 'show'])->name('grup.show');
    Route::get   ('grup-soal/{id}/edit', [\App\Http\Controllers\Admin\GrupSoalController::class, 'edit'])->name('grup.edit');
    Route::put   ('grup-soal/{id}',      [\App\Http\Controllers\Admin\GrupSoalController::class, 'update'])->name('grup.update');
    Route::delete('grup-soal/{id}',      [\App\Http\Controllers\Admin\GrupSoalController::class, 'destroy'])->name('grup.destroy');

    // Paket Soal
    // ── Reading Passages ──
    Route::get   ('passage',               [\App\Http\Controllers\Admin\PassageController::class, 'index'])->name('passage.index');
    Route::get   ('passage/create',        [\App\Http\Controllers\Admin\PassageController::class, 'create'])->name('passage.create');
    Route::post  ('passage',               [\App\Http\Controllers\Admin\PassageController::class, 'store'])->name('passage.store');
    Route::get   ('passage/{id}',          [\App\Http\Controllers\Admin\PassageController::class, 'show'])->name('passage.show');
    Route::get   ('passage/{id}/edit',    [\App\Http\Controllers\Admin\PassageController::class, 'edit'])->name('passage.edit');
    Route::put   ('passage/{id}',          [\App\Http\Controllers\Admin\PassageController::class, 'update'])->name('passage.update');
    Route::delete('passage/{id}',          [\App\Http\Controllers\Admin\PassageController::class, 'destroy'])->name('passage.destroy');
    Route::post  ('passage/{id}/soal',    [\App\Http\Controllers\Admin\PassageController::class, 'storeSoal'])->name('passage.storeSoal');
    Route::delete('passage/soal/{soalId}',[\App\Http\Controllers\Admin\PassageController::class, 'destroySoal'])->name('passage.destroySoal');
    Route::get   ('paket-soal',                 [\App\Http\Controllers\Admin\PaketSoalController::class, 'index'])->name('paket.index');
    Route::get   ('paket-soal/create',           [\App\Http\Controllers\Admin\PaketSoalController::class, 'create'])->name('paket.create');
    Route::post  ('paket-soal',                  [\App\Http\Controllers\Admin\PaketSoalController::class, 'store'])->name('paket.store');
    Route::get   ('paket-soal/{id}/edit',        [\App\Http\Controllers\Admin\PaketSoalController::class, 'edit'])->name('paket.edit');
    Route::post  ('paket-soal/{id}/validate',    [\App\Http\Controllers\Admin\PaketSoalController::class, 'validatePaket'])->name('paket.validate');
    Route::delete('paket-soal/{id}',             [\App\Http\Controllers\Admin\PaketSoalController::class, 'destroy'])->name('paket.destroy');
    Route::post  ('paket-soal/{id}/add-soal',    [\App\Http\Controllers\Admin\PaketSoalController::class, 'addSoal'])->name('paket.addSoal');
    Route::post  ('paket-soal/{id}/remove-soal', [\App\Http\Controllers\Admin\PaketSoalController::class, 'removeSoal'])->name('paket.removeSoal');

    // Materi
    Route::get   ('materi',           [\App\Http\Controllers\Admin\MateriController::class, 'index'])->name('materi.index');
    Route::get   ('materi/create',    [\App\Http\Controllers\Admin\MateriController::class, 'create'])->name('materi.create');
    Route::post  ('materi',           [\App\Http\Controllers\Admin\MateriController::class, 'store'])->name('materi.store');
    Route::get   ('materi/{id}/edit', [\App\Http\Controllers\Admin\MateriController::class, 'edit'])->name('materi.edit');
    Route::put   ('materi/{id}',      [\App\Http\Controllers\Admin\MateriController::class, 'update'])->name('materi.update');
    Route::delete('materi/{id}',      [\App\Http\Controllers\Admin\MateriController::class, 'destroy'])->name('materi.destroy');

  // Sesi Tes
Route::get   ('sesi',             [\App\Http\Controllers\Admin\SesiTesController::class, 'index'])->name('sesi.index');
Route::get   ('sesi/create',      [\App\Http\Controllers\Admin\SesiTesController::class, 'create'])->name('sesi.create');
Route::post  ('sesi',             [\App\Http\Controllers\Admin\SesiTesController::class, 'store'])->name('sesi.store');
Route::get   ('sesi/{id}/edit',   [\App\Http\Controllers\Admin\SesiTesController::class, 'edit'])->name('sesi.edit');
Route::put   ('sesi/{id}',        [\App\Http\Controllers\Admin\SesiTesController::class, 'update'])->name('sesi.update');
Route::delete('sesi/{id}',        [\App\Http\Controllers\Admin\SesiTesController::class, 'destroy'])->name('sesi.destroy');
Route::patch ('sesi/{id}/toggle', [\App\Http\Controllers\Admin\SesiTesController::class, 'toggleAktif'])->name('sesi.toggle');

// ← TAMBAHKAN 3 BARIS INI
Route::get   ('sesi/{id}',               [\App\Http\Controllers\Admin\SesiTesController::class, 'show'])->name('sesi.show');
Route::post  ('sesi/peserta/{id}/hadir', [\App\Http\Controllers\Admin\SesiTesController::class, 'tandaiHadir'])->name('sesi.tandai-hadir');
Route::post  ('sesi/peserta/{id}/reset', [\App\Http\Controllers\Admin\SesiTesController::class, 'resetHadir'])->name('sesi.reset-hadir');

    // Pendaftaran
    Route::get  ('pendaftaran',                  [\App\Http\Controllers\Admin\PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get  ('pendaftaran/{id}',             [\App\Http\Controllers\Admin\PendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::post ('pendaftaran/{id}/konfirmasi',  [\App\Http\Controllers\Admin\PendaftaranController::class, 'konfirmasi'])->name('pendaftaran.konfirmasi');
    Route::post ('pendaftaran/{id}/tolak',       [\App\Http\Controllers\Admin\PendaftaranController::class, 'tolak'])->name('pendaftaran.tolak');
    Route::post ('pendaftaran/{id}/absen',       [\App\Http\Controllers\Admin\PendaftaranController::class, 'tandaiAbsen'])->name('pendaftaran.absen');

    // User Management
    Route::get   ('user',                   [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('user.index');
    Route::get   ('user/{id}',              [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('user.show');
    Route::patch ('user/{id}/toggle',       [\App\Http\Controllers\Admin\UserController::class, 'toggleAktif'])->name('user.toggle');
    Route::post  ('user/{id}/aktifkan', [\App\Http\Controllers\Admin\UserController::class, 'aktifkanDariPendaftaran'])->name('user.aktifkan');
    Route::delete('user/{id}',              [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('user.destroy');
    Route::post  ('user/{id}/reset-cooldown', [\App\Http\Controllers\Admin\ResetAksesTesController::class, 'resetCooldown'])->name('user.reset-cooldown');
    Route::post  ('user/{id}/reset-absen',    [\App\Http\Controllers\Admin\ResetAksesTesController::class, 'resetAbsen'])->name('user.reset-absen');

    // Reset Akses Tes
    Route::post('percobaan/{id}/reset-akses', [\App\Http\Controllers\Admin\ResetAksesTesController::class, 'reset'])->name('percobaan.reset-akses');

    // Monitoring
    Route::get  ('monitoring',                    [\App\Http\Controllers\Admin\MonitoringController::class, 'index'])->name('monitoring.index');
    Route::patch('monitoring/nonaktifkan/{userId}',[\App\Http\Controllers\Admin\MonitoringController::class, 'nonaktifkanUser'])->name('monitoring.nonaktifkan');
    Route::post ('monitoring/diskualifikasi/{id}', [\App\Http\Controllers\Admin\MonitoringController::class, 'diskualifikasi'])->name('monitoring.diskualifikasi');

    // Laporan
    Route::get ('laporan',                  [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get ('laporan/cari',             [\App\Http\Controllers\Admin\LaporanController::class, 'cariNomor'])->name('laporan.cari');
    Route::post('laporan/cari',             [\App\Http\Controllers\Admin\LaporanController::class, 'cariNomor']);
    Route::get ('laporan/{sesiId}/export',  [\App\Http\Controllers\Admin\LaporanController::class, 'exportExcel'])->name('laporan.export');

    // Passage (Reading Parent-Child)
    Route::get   ('soal/passage/create',     [\App\Http\Controllers\Admin\PassageController::class, 'create'])->name('admin.soal.passage.create');
    Route::post  ('soal/passage',            [\App\Http\Controllers\Admin\PassageController::class, 'store'])->name('admin.soal.passage.store');
    Route::get   ('soal/passage/{id}/edit',  [\App\Http\Controllers\Admin\PassageController::class, 'edit'])->name('admin.soal.passage.edit');
    Route::put   ('soal/passage/{id}',       [\App\Http\Controllers\Admin\PassageController::class, 'update'])->name('admin.soal.passage.update');
    Route::delete('soal/passage/{id}',       [\App\Http\Controllers\Admin\PassageController::class, 'destroy'])->name('admin.soal.passage.destroy');

    // Konversi Skor TOEFL ITP
    Route::get ('konversi-skor',      [\App\Http\Controllers\Admin\KonversiSkorController::class, 'index'])->name('admin.konversi-skor.index');
    Route::post('konversi-skor/bulk', [\App\Http\Controllers\Admin\KonversiSkorController::class, 'storeBulk'])->name('admin.konversi-skor.bulk');

    // Evaluasi
    Route::get   ('evaluasi',              [\App\Http\Controllers\Admin\EvaluasiController::class, 'index'])->name('evaluasi.index');
    Route::post  ('evaluasi',              [\App\Http\Controllers\Admin\EvaluasiController::class, 'store'])->name('evaluasi.store');
    Route::patch ('evaluasi/{id}/publish', [\App\Http\Controllers\Admin\EvaluasiController::class, 'publish'])->name('evaluasi.publish');
    Route::delete('evaluasi/{id}',         [\App\Http\Controllers\Admin\EvaluasiController::class, 'destroy'])->name('evaluasi.destroy');

    // Pengumuman
    Route::get   ('pengumuman',             [\App\Http\Controllers\Admin\PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post  ('pengumuman',             [\App\Http\Controllers\Admin\PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::patch ('pengumuman/{id}/toggle', [\App\Http\Controllers\Admin\PengumumanController::class, 'togglePublish'])->name('pengumuman.toggle');
    Route::delete('pengumuman/{id}',        [\App\Http\Controllers\Admin\PengumumanController::class, 'destroy'])->name('pengumuman.destroy');
});