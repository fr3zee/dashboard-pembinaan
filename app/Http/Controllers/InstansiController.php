<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreInstansiRequest;
use App\Models\Instansi;
use App\Models\KategoriInstansi;
class InstansiController extends Controller {
 public function index(){return view('master.instansi.index',['items'=>Instansi::with('kategoriInstansi')->withCount('pembinaan')->latest()->paginate(15)]);}
 public function create(){return view('master.instansi.create',['categories'=>KategoriInstansi::where('status_aktif',true)->orderBy('nama')->get()]);}
 public function store(StoreInstansiRequest $request){Instansi::create($request->validated()+['status_aktif'=>$request->boolean('status_aktif',true)]);return redirect()->route('instansi.index')->with('success','Instansi berhasil ditambahkan.');}
 public function edit(Instansi $instansi){return view('master.instansi.edit',['item'=>$instansi,'categories'=>KategoriInstansi::where('status_aktif',true)->orderBy('nama')->get()]);}
 public function update(StoreInstansiRequest $request,Instansi $instansi){$instansi->update($request->validated()+['status_aktif'=>$request->boolean('status_aktif')]);return redirect()->route('instansi.index')->with('success','Instansi berhasil diperbarui.');}
 public function destroy(Instansi $instansi){if($instansi->pembinaan()->exists())return back()->with('error','Instansi tidak dapat dihapus karena masih digunakan oleh data pembinaan.');$instansi->delete();return back()->with('success','Instansi berhasil dihapus permanen.');}
}
