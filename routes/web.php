<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\KategoriInstansiController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PembinaanController;
use App\Http\Controllers\PembinaanFileController;
use App\Http\Controllers\PembinaanFileCatalogController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\ExportLaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembinaanChangeRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
Route::get('/', fn () => redirect()->route('login'));
Route::middleware('guest')->group(function () { Route::get('/login', [LoginController::class, 'create'])->name('login'); Route::post('/login', [LoginController::class, 'store'])->name('login.store'); Route::get('/lupa-password', [PasswordResetLinkController::class, 'create'])->name('password.request'); Route::post('/lupa-password', [PasswordResetLinkController::class, 'store'])->name('password.email'); Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset'); Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store'); });
Route::get('/profil', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::put('/profil', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/admin-check', fn () => 'admin')->middleware('role:admin');
    Route::post('/write-check', fn () => 'write')->middleware('role:admin,pelaksana');
    Route::get('/monitor-check', fn () => 'monitor')->middleware('role:admin,pelaksana,monitoring');
    Route::middleware('role:admin')->group(function () {
         Route::get('/permintaan-perubahan', [PembinaanChangeRequestController::class, 'index'])->name('change-requests.index'); Route::patch('/permintaan-perubahan/{changeRequest}/approve', [PembinaanChangeRequestController::class, 'approve'])->name('change-requests.approve'); Route::patch('/permintaan-perubahan/{changeRequest}/reject', [PembinaanChangeRequestController::class, 'reject'])->name('change-requests.reject');
        Route::resource('users', UserController::class)->except(['show','destroy']); Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status'); Route::resource('kategori-instansi', KategoriInstansiController::class)->except('show')->parameters(['kategori-instansi' => 'kategori_instansi']); Route::resource('instansi', InstansiController::class)->except('show'); Route::resource('pegawai', PegawaiController::class)->except('show');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pembinaan', PembinaanController::class)->except(['destroy']); Route::post('/pembinaan/{pembinaan}/change-requests', [PembinaanChangeRequestController::class, 'store'])->name('pembinaan.change-requests.store');
    Route::get('/katalog-file', [PembinaanFileCatalogController::class, 'index'])->name('pembinaan-files.index');
    Route::get('/katalog-materi', fn () => redirect()->route('pembinaan-files.laporan'))->name('pembinaan-files.materi');
    Route::get('/katalog-materi/{pembinaan}', [PembinaanFileCatalogController::class, 'materiShow'])->name('pembinaan-files.materi.show');
    Route::get('/katalog-laporan', [PembinaanFileCatalogController::class, 'laporan'])->name('pembinaan-files.laporan');
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('/export-laporan', [ExportLaporanController::class, 'index'])->name('export-laporan.index');
    Route::get('/template-laporan-pembinaan', [ExportLaporanController::class, 'template'])->name('template-laporan-pembinaan');
    Route::get('/export-laporan/excel', [ExportLaporanController::class, 'excel'])->name('export-laporan.excel');
    Route::post('/pembinaan/{pembinaan}/files', [PembinaanFileController::class, 'store'])->name('pembinaan.files.store');
    Route::get('/pembinaan-files/{pembinaanFile}/preview', [PembinaanFileController::class, 'preview'])->name('pembinaan.files.preview');
    Route::get('/pembinaan-files/{pembinaanFile}/download', [PembinaanFileController::class, 'download'])->name('pembinaan.files.download');
     Route::delete('/pembinaan-files/{pembinaanFile}', [PembinaanFileController::class, 'destroy'])->name('pembinaan.files.destroy');
});

