<?php
namespace App\Http\Controllers;
use App\Models\Pembinaan;
use Illuminate\Http\Request;
class DashboardController extends Controller {
 public function index(Request $request) {
  $year=(int)$request->input('year',now()->year); if($year<2000||$year>2100)$year=now()->year;
  $base= Pembinaan::query();
  $rows=(clone $base)->whereYear('tanggal_mulai',$year)->with('pegawai:id,nama_lengkap')->get();
  $years=(clone $base)->selectRaw('YEAR(tanggal_mulai) year')->distinct()->pluck('year')->map(fn($v)=>(int)$v)->push(now()->year)->unique()->sortDesc()->values();
  $quarter=array_fill(0,4,0);$services=[];$people=[];$regions=[];
  foreach($rows as $row){$quarter[(int)floor(($row->tanggal_mulai->month-1)/3)]++;$key=$row->layanan?:'Tidak dikategorikan';$services[$key]=($services[$key]??0)+1;$region=$row->provinsi?:'Lokasi tidak diisi';$regions[$region]=($regions[$region]??0)+1;foreach($row->pegawai as $person)$people[$person->id]=[$person->nama_lengkap,($people[$person->id][1]??0)+1];}
  uasort($people,fn($a,$b)=>$b[1]<=>$a[1] ?: strcmp($a[0],$b[0]));
  return view('dashboard',compact('year','years','rows','quarter','services','people','regions'))->with(['totalPembinaan'=>$rows->count(),'totalInstansi'=>$rows->pluck('instansi_id')->filter()->unique()->count(),'totalNarasumber'=>count($people)]);
 }
}
