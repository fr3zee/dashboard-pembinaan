<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreKategoriInstansiRequest;
use App\Models\KategoriInstansi;
class KategoriInstansiController extends Controller {
 public function index(){return view('master.kategori-instansi.index',['items'=>KategoriInstansi::withCount('instansi')->latest()->paginate(15)]);}
 public function create(){return view('master.kategori-instansi.create');}
 public function store(StoreKategoriInstansiRequest $request){KategoriInstansi::create($request->validated()+['status_aktif'=>$request->boolean('status_aktif',true)]);return redirect()->route('kategori-instansi.index')->with('success','Kategori berhasil ditambahkan.');}
 public function edit(KategoriInstansi $kategori_instansi){return view('master.kategori-instansi.edit',['item'=>$kategori_instansi]);}
 public function update(StoreKategoriInstansiRequest $request,KategoriInstansi $kategori_instansi){$kategori_instansi->update($request->validated()+['status_aktif'=>$request->boolean('status_aktif')]);return redirect()->route('kategori-instansi.index')->with('success','Kategori berhasil diperbarui.');}
 public function destroy(KategoriInstansi $kategori_instansi){if($kategori_instansi->instansi()->exists())return back()->with('error','Kategori tidak dapat dihapus karena masih digunakan oleh instansi.');$kategori_instansi->delete();return back()->with('success','Kategori berhasil dihapus permanen.');}
}
