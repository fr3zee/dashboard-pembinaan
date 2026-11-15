<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class ProfileController extends Controller {
 public function edit(Request $request){return view('profile.edit',['user'=>$request->user()]);}
 public function update(Request $request){$user=$request->user();$data=$request->validate(['name'=>['required','string','max:255'],'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($user->id)],'current_password'=>['nullable','required_with:password','current_password'],'password'=>['nullable','string','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/','confirmed'],'avatar'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:2048']]);$user->fill(['name'=>$data['name'],'email'=>$data['email']]);if($request->hasFile('avatar')){$user->avatar=$request->file('avatar')->store('avatars','public');}if(!empty($data['password']))$user->password=Hash::make($data['password']);$user->save();return back()->with('success','Profil berhasil diperbarui.');}
}
