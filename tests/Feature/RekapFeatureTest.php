<?php
namespace Tests\Feature;
use App\Models\{Instansi,KategoriInstansi,Pegawai,Pembinaan,User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class RekapFeatureTest extends TestCase {
 use RefreshDatabase;
 private function make(User $u,string $date,string $name='Instansi Rekap',array $pegawai=[]): Pembinaan { $k=KategoriInstansi::create(['nama'=>'Kategori '.uniqid(),'status_aktif'=>true]); $i=Instansi::create(['kategori_instansi_id'=>$k->id,'nama'=>$name,'status_aktif'=>true]); $p=Pembinaan::create(['instansi_id'=>$i->id,'created_by'=>$u->id,'tanggal_mulai'=>$date,'tanggal_selesai'=>$date,'metode_pelaksanaan'=>'Daring','layanan'=>'Sosialisasi','status_dokumen'=>'Selesai']); $p->pegawai()->sync($pegawai); return $p; }
 public function test_guest_denied_and_roles_can_read(): void { $this->get('/rekap')->assertRedirect('/login'); foreach(['admin','monitoring'] as $role){$u=User::factory()->role($role)->create();$this->actingAs($u)->get('/rekap')->assertOk();} }
  public function test_filters_and_summary_use_scoped_data_without_duplicates(): void { $admin=User::factory()->role('admin')->create(); $a=Pegawai::create(['nama_lengkap'=>'Narasumber A','status_aktif'=>true]); $b=Pegawai::create(['nama_lengkap'=>'Narasumber B','status_aktif'=>true]); $this->make($admin,'2026-01-10','Instansi Sama',[$a->id,$b->id]); $this->make($admin,'2026-05-10','Instansi Sama',[$a->id]); $this->make($admin,'2025-08-10','Instansi Lama',[$b->id]); $this->actingAs($admin)->get('/rekap?tahun=2026')->assertOk()->assertSee('Total Kegiatan')->assertSee('Instansi Sama')->assertDontSee('Instansi Lama')->assertSee('<strong>2</strong>', false)->assertSee('<strong>2</strong>', false); $this->actingAs($admin)->get('/rekap?tahun=2026&triwulan=2')->assertOk()->assertSee('Instansi Sama')->assertDontSee('Instansi Lama'); }
 public function test_pelaksana_ownership_scopes_rows_and_summary(): void { $one=User::factory()->role('pelaksana')->create(); $two=User::factory()->role('pelaksana')->create(); $this->make($one,'2026-02-01','Milik Saya'); $this->make($two,'2026-02-02','Milik Orang'); $this->actingAs($one)->get('/rekap?tahun=2026')->assertOk()->assertSee('Milik Saya')->assertDontSee('Milik Orang'); }
 public function test_empty_state_and_actual_status_render(): void { $u=User::factory()->role('admin')->create(); $this->actingAs($u)->get('/rekap?tahun=2099')->assertOk()->assertSee('Belum ada data rekap'); $this->make($u,'2026-03-01'); $this->actingAs($u)->get('/rekap?tahun=2026&triwulan=1')->assertSee('Selesai'); }
}
