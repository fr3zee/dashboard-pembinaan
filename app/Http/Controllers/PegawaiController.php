<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePegawaiRequest;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index(){return view('master.pegawai.index',['items'=>Pegawai::latest()->paginate(15)]);}
    public function create(){return view('master.pegawai.create',['item'=>null]);}
    public function store(StorePegawaiRequest $request){$data=$request->validated();if($request->hasFile('foto'))$data['foto']=$request->file('foto')->store('pegawai','public');Pegawai::create($data+['status_aktif'=>$request->boolean('status_aktif',true)]);return redirect()->route('pegawai.index')->with('success','Pegawai berhasil ditambahkan.');}
    public function edit(Pegawai $pegawai){return view('master.pegawai.edit',['item'=>$pegawai]);}
    public function update(StorePegawaiRequest $request,Pegawai $pegawai){$data=$request->validated();if($request->hasFile('foto'))$data['foto']=$request->file('foto')->store('pegawai','public');$pegawai->update($data+['status_aktif'=>$request->boolean('status_aktif')]);return redirect()->route('pegawai.index')->with('success','Pegawai berhasil diperbarui.');}
    public function destroy(Pegawai $pegawai){$pegawai->update(['status_aktif'=>false]);return back()->with('success','Pegawai dinonaktifkan.');}
}
