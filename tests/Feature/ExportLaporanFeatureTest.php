<?php
namespace Tests\Feature;
use App\Models\{Instansi,KategoriInstansi,Pegawai,Pembinaan,User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ExportLaporanFeatureTest extends TestCase {
 use RefreshDatabase;
 private function row(User $u,string $date,string $name='Export Instansi',?Pegawai $p=null): Pembinaan { $k=KategoriInstansi::create(['nama'=>'Kategori '.uniqid(),'status_aktif'=>true]); $i=Instansi::create(['kategori_instansi_id'=>$k->id,'nama'=>$name,'status_aktif'=>true]); $x=Pembinaan::create(['instansi_id'=>$i->id,'created_by'=>$u->id,'tanggal_mulai'=>$date,'tanggal_selesai'=>$date,'metode_pelaksanaan'=>'Daring','layanan'=>'Sosialisasi','status_dokumen'=>'Selesai']); if($p)$x->pegawai()->attach($p); return $x; }
 public function test_guest_denied_and_roles_read_export(): void {$this->get('/export-laporan')->assertRedirect('/login'); foreach(['admin','monitoring'] as $r){$u=User::factory()->role($r)->create();$this->actingAs($u)->get('/export-laporan')->assertOk();}}
 public function test_filters_preview_and_download_excel(): void {$u=User::factory()->role('admin')->create();$p=Pegawai::create(['nama_lengkap'=>'Export Narasumber','status_aktif'=>true]);$this->row($u,'2026-05-02','Export Sama',$p);$this->row($u,'2025-05-02','Export Lama');$this->actingAs($u)->get('/export-laporan?tahun=2026&triwulan=2&pegawai_id='.$p->id)->assertOk()->assertSee('Export Sama')->assertDontSee('Export Lama');$this->actingAs($u)->get('/export-laporan/excel?tahun=2026&triwulan=2&pegawai_id='.$p->id)->assertOk()->assertHeader('content-type','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');}
 public function test_pelaksana_export_is_scoped(): void {$one=User::factory()->role('pelaksana')->create();$two=User::factory()->role('pelaksana')->create();$this->row($one,'2026-01-01','Milik Saya');$this->row($two,'2026-01-01','Milik Orang');$this->actingAs($one)->get('/export-laporan')->assertSee('Milik Saya')->assertDontSee('Milik Orang');}
}