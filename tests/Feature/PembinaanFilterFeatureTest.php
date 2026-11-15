<?php
namespace Tests\Feature;
use App\Models\Instansi;
use App\Models\Pegawai;
use App\Models\Pembinaan;
use App\Models\KategoriInstansi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class PembinaanFilterFeatureTest extends TestCase {
 use RefreshDatabase;
 private function setupData(User $owner,string $name,bool $active=true): array { $cat=KategoriInstansi::create(['nama'=>$name.' Cat','status_aktif'=>true]); $inst=Instansi::create(['kategori_instansi_id'=>$cat->id,'nama'=>$name.' Inst','status_aktif'=>true]); return [$cat,$inst]; }
 private function item(User $u,Instansi $i,string $service,string $date,string $status='Tahap Persiapan'): Pembinaan { return Pembinaan::create(['instansi_id'=>$i->id,'created_by'=>$u->id,'tanggal_mulai'=>$date,'metode_pelaksanaan'=>'Daring','layanan'=>$service,'status_dokumen'=>$status]); }
 public function test_filters_and_search_are_scoped_for_pelaksana(): void { $u=User::factory()->role('pelaksana')->create();$v=User::factory()->role('pelaksana')->create();[, $i1]=$this->setupData($u,'Satu');[, $i2]=$this->setupData($u,'Dua');$own=$this->item($u,$i1,'TARGET','2026-01-10','Selesai');$other=$this->item($v,$i1,'FOREIGN-TARGET','2026-01-10','Selesai');$response=$this->actingAs($u)->get('/pembinaan?instansi_id='.$i1->id.'&status_dokumen=Selesai&tanggal_mulai=2026-01-01&tanggal_selesai=2026-01-31&q=TARGET');$response->assertSee('TARGET')->assertSee('Satu Inst')->assertDontSee('FOREIGN-TARGET');$response=$this->get('/pembinaan?instansi_id='.$i2->id.'&q=FOREIGN-TARGET');$response->assertSee('Belum ada pembinaan.');$this->assertSame(0,Pembinaan::where('created_by',$u->id)->where('instansi_id',$i2->id)->count()); }
 public function test_admin_and_monitoring_see_all_filtered_results(): void { $a=User::factory()->role('admin')->create();$m=User::factory()->role('monitoring')->create();[, $i]=$this->setupData($a,'All');$one=$this->item($a,$i,'ADMIN-ROW','2026-02-01');$two=$this->item(User::factory()->role('pelaksana')->create(),$i,'USER-ROW','2026-02-02');foreach([$a,$m] as $user)$this->actingAs($user)->get('/pembinaan?instansi_id='.$i->id)->assertSee('ADMIN-ROW')->assertSee('USER-ROW'); }
 public function test_pelaksana_pagination_never_exposes_other_owner(): void { $u=User::factory()->role('pelaksana')->create();$v=User::factory()->role('pelaksana')->create();[, $i]=$this->setupData($u,'Page');for($n=0;$n<16;$n++)$this->item($u,$i,'OWN-'.$n,'2026-03-'.str_pad((string)(($n%9)+1),2,'0',STR_PAD_LEFT));$this->item($v,$i,'FOREIGN','2026-03-01');$this->actingAs($u)->get('/pembinaan')->assertSee('OWN-0')->assertDontSee('FOREIGN');$this->get('/pembinaan?page=2')->assertDontSee('FOREIGN'); }
}
