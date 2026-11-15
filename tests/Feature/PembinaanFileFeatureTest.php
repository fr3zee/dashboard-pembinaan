<?php
namespace Tests\Feature;
use App\Models\Instansi;
use App\Models\KategoriInstansi;
use App\Models\Pembinaan;
use App\Models\PembinaanFile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class PembinaanFileFeatureTest extends TestCase {
 use RefreshDatabase;
 private function pembinaan(User $owner): Pembinaan { $cat=KategoriInstansi::create(['nama'=>'File Cat '.$owner->id,'status_aktif'=>true]);$inst=Instansi::create(['kategori_instansi_id'=>$cat->id,'nama'=>'File Inst '.$owner->id,'status_aktif'=>true]);return Pembinaan::create(['instansi_id'=>$inst->id,'created_by'=>$owner->id,'tanggal_mulai'=>'2026-10-01','metode_pelaksanaan'=>'Daring','layanan'=>'Materi','status_dokumen'=>'Tahap Persiapan']); }
 private function upload(): UploadedFile { return UploadedFile::fake()->createWithContent('materi.pdf', '%PDF-1.4 test'); }
 public function test_guest_cannot_upload_or_download(): void { $owner=User::factory()->role('pelaksana')->create();$item=$this->pembinaan($owner);$this->post('/pembinaan/'.$item->id.'/files',['file'=>$this->upload(),'jenis'=>'materi','visibilitas'=>'Private'])->assertRedirect('/login');$file= PembinaanFile::create(['pembinaan_id'=>$item->id,'uploaded_by'=>$owner->id,'jenis'=>'materi','nama_asli'=>'x.pdf','path'=>'missing/x.pdf','mime_type'=>'application/pdf','ukuran'=>1,'visibilitas'=>'Private']);$this->get('/pembinaan-files/'.$file->id.'/download')->assertRedirect('/login'); }
 public function test_admin_uploads_and_downloads_private_file_with_metadata(): void { Storage::fake('local');$admin=User::factory()->role('admin')->create();$item=$this->pembinaan($admin);$response=$this->actingAs($admin)->post('/pembinaan/'.$item->id.'/files',['file'=>$this->upload(),'jenis'=>'laporan','visibilitas'=>'Private']);$response->assertRedirect();$file=PembinaanFile::firstOrFail();$this->assertSame($admin->id,$file->uploaded_by);$this->assertSame('laporan',$file->jenis);$this->assertSame('materi.pdf',$file->nama_asli);Storage::disk('local')->assertExists($file->path);$this->get('/pembinaan-files/'.$file->id.'/download')->assertOk(); }
 public function test_foreign_pelaksana_cannot_access_file_and_monitoring_cannot_upload(): void { Storage::fake('local');$owner=User::factory()->role('pelaksana')->create();$other=User::factory()->role('pelaksana')->create();$item=$this->pembinaan($owner);$file=PembinaanFile::create(['pembinaan_id'=>$item->id,'uploaded_by'=>$owner->id,'jenis'=>'materi','nama_asli'=>'x.pdf','path'=>'x.pdf','mime_type'=>'application/pdf','ukuran'=>1,'visibilitas'=>'Private']);$this->actingAs($other)->get('/pembinaan-files/'.$file->id.'/download')->assertForbidden();$this->actingAs(User::factory()->role('monitoring')->create())->post('/pembinaan/'.$item->id.'/files',['file'=>$this->upload(),'jenis'=>'materi','visibilitas'=>'Private'])->assertForbidden(); }
 public function test_invalid_extension_and_size_are_rejected(): void { $admin=User::factory()->role('admin')->create();$item=$this->pembinaan($admin);$this->actingAs($admin)->post('/pembinaan/'.$item->id.'/files',['file'=>UploadedFile::fake()->create('script.exe',10,'application/octet-stream'),'jenis'=>'materi','visibilitas'=>'Private'])->assertSessionHasErrors('file');$this->post('/pembinaan/'.$item->id.'/files',['file'=>UploadedFile::fake()->create('large.pdf',10241,'application/pdf'),'jenis'=>'materi','visibilitas'=>'Private'])->assertSessionHasErrors('file'); }
}
