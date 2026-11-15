<?php
namespace Tests\Feature;
use App\Models\Instansi;
use App\Models\Pegawai;
use App\Models\Pembinaan;
use App\Models\KategoriInstansi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class PembinaanFeatureTest extends TestCase {
 use RefreshDatabase;
 private function data(User $user, array $extra=[]): array { $category=KategoriInstansi::firstOrCreate(['nama'=>'Kategori Test'],['status_aktif'=>true]); $instansi=Instansi::firstOrCreate(['kategori_instansi_id'=>$category->id,'nama'=>'Instansi Test'],['status_aktif'=>true]); return array_merge(['instansi_id'=>$instansi->id,'tanggal_mulai'=>'2026-10-01','tanggal_selesai'=>'2026-10-02','metode_pelaksanaan'=>'Daring','lokasi'=>'Jakarta','provinsi'=>'DKI','kota'=>'Jakarta','layanan'=>'Sosialisasi','status_dokumen'=>'Tahap Persiapan','catatan_hasil'=>'Catatan'], $extra); }
 public function test_guest_is_rejected(): void { foreach(['/pembinaan','/pembinaan/create'] as $url)$this->get($url)->assertRedirect('/login'); }
 public function test_admin_and_pelaksana_create_and_owner_is_server_side(): void { $admin=User::factory()->role('admin')->create(); $this->actingAs($admin)->post('/pembinaan',$this->data($admin,['created_by'=>999999]))->assertRedirect(); $item=Pembinaan::latest('id')->first(); $this->assertSame($admin->id,$item->created_by); $owner=User::factory()->role('pelaksana')->create(); $this->actingAs($owner)->post('/pembinaan',$this->data($owner))->assertRedirect(); $this->assertSame($owner->id,Pembinaan::latest('id')->first()->created_by); }
 public function test_ownership_blocks_foreign_detail_and_edit_and_scopes_index(): void { $one=User::factory()->role('pelaksana')->create(); $two=User::factory()->role('pelaksana')->create(); $foreign=Pembinaan::create($this->data($two)+['created_by'=>$two->id]); $own=Pembinaan::create($this->data($one,['layanan'=>'Milik Saya'])+['created_by'=>$one->id]); $this->actingAs($one)->get('/pembinaan')->assertOk()->assertSee('Milik Saya')->assertSee('Instansi Test'); $this->get('/pembinaan/'.$foreign->id)->assertForbidden(); $this->get('/pembinaan/'.$foreign->id.'/edit')->assertForbidden(); $this->put('/pembinaan/'.$foreign->id,$this->data($one))->assertForbidden(); }
 public function test_monitoring_reads_but_cannot_write(): void { $viewer=User::factory()->role('monitoring')->create(); $item=Pembinaan::create($this->data($viewer)+['created_by'=>$viewer->id]); $this->actingAs($viewer)->get('/pembinaan')->assertOk(); $this->post('/pembinaan',$this->data($viewer))->assertForbidden(); $this->put('/pembinaan/'.$item->id,$this->data($viewer))->assertForbidden(); }
 public function test_validation_and_pivot_sync(): void { $user=User::factory()->role('pelaksana')->create(); $pegawaiA=Pegawai::create(['nama_lengkap'=>'A','status_aktif'=>true]); $pegawaiB=Pegawai::create(['nama_lengkap'=>'B','status_aktif'=>true]); $data=$this->data($user,['pegawai'=>[$pegawaiA->id,$pegawaiB->id]]); $this->actingAs($user)->post('/pembinaan',$data)->assertRedirect(); $item=Pembinaan::latest('id')->first(); $this->assertCount(2,$item->pegawai); $this->put('/pembinaan/'.$item->id,$this->data($user,['pegawai'=>[$pegawaiB->id]]))->assertRedirect(); $this->assertSame([$pegawaiB->id],$item->fresh()->pegawai->pluck('id')->all()); $this->post('/pembinaan',$this->data($user,['instansi_id'=>999999,'pegawai'=>[999999],'tanggal_selesai'=>'2026-09-01','status_dokumen'=>'Invalid']))->assertSessionHasErrors(['instansi_id','pegawai.0','tanggal_selesai','status_dokumen']); }
}
