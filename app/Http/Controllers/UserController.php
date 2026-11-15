<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class UserController extends Controller {
 public function index(Request $request){$query=User::query()->when($request->q,fn($q,$v)=>$q->where(fn($w)=>$w->where('name','like',"%$v%")->orWhere('email','like',"%$v%")->orWhere('role','like',"%$v%")))->when($request->role,fn($q,$v)=>$q->where('role',$v))->when($request->status,fn($q,$v)=>$q->where('status_aktif',$v==='aktif'));return view('users.index',['items'=>$query->orderBy('name')->paginate(15)->withQueryString(),'total'=>User::count()]);}
 public function create(){return view('users.form');}
 public function store(Request $request){$data=$this->data($request);$data['password']=Hash::make($data['password']);User::create($data);return redirect()->route('users.index')->with('success','Akun berhasil ditambahkan.');}
 public function edit(User $user){return view('users.form',compact('user'));}
 public function toggleStatus(User $user){abort_if((int)$user->id === (int)request()->user()->id, 403, 'Akun yang sedang digunakan tidak dapat dinonaktifkan.');$user->update(['status_aktif'=>!$user->status_aktif]);return back()->with('success','Status akun berhasil diperbarui.');}
 public function update(Request $request,User $user){$data=$this->data($request,$user);if(empty($data['password']))unset($data['password']);else $data['password']=Hash::make($data['password']);$user->update($data);return redirect()->route('users.index')->with('success','Akun berhasil diperbarui.');}
 private function data(Request $request,?User $user=null):array{$roles=$user?['admin','pelaksana','monitoring']:['pelaksana','monitoring'];$passwordRules=[$user?'nullable':'required','string','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/','confirmed'];return $request->validate(['name'=>['required','string','max:255'],'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($user?->id)],'nomor_telepon'=>['nullable','string','max:30'],'nip'=>['nullable','string','max:50'],'unit_kerja'=>['nullable','string','max:255'],'instansi'=>['nullable','string','max:255'],'role'=>['required',Rule::in($roles)],'status_aktif'=>['nullable','boolean'],'password'=>$passwordRules]);}
}
