<?php
namespace App\Http\Controllers;
use App\Models\Pembinaan;
use App\Models\PembinaanChangeRequest;
use Illuminate\Http\Request;
class PembinaanChangeRequestController extends Controller {
 public function store(Request $request, Pembinaan $pembinaan) { abort_unless($request->user()->role==='pelaksana' && $pembinaan->created_by===$request->user()->id && $pembinaan->workflow_status==='submitted',403); $data=$request->validate(['reason'=>['required','string','min:10','max:2000']]); PembinaanChangeRequest::create(['pembinaan_id'=>$pembinaan->id,'requested_by'=>$request->user()->id,'reason'=>$data['reason']]); return back()->with('success','Permintaan perubahan berhasil dikirim ke Admin.'); }
 public function index() { abort_unless(auth()->user()->role==='admin',403); return view('change-requests.index',['items'=>PembinaanChangeRequest::with(['pembinaan.instansi','requester'])->where('status','pending')->latest()->get()]); }
 public function approve(PembinaanChangeRequest $changeRequest) { abort_unless(auth()->user()->role==='admin',403); $changeRequest->update(['status'=>'approved','reviewed_by'=>auth()->id()]); $changeRequest->pembinaan()->update(['workflow_status'=>'draft']); return back()->with('success','Permintaan disetujui. User dapat mengedit data.'); }
 public function reject(Request $request, PembinaanChangeRequest $changeRequest) { abort_unless(auth()->user()->role==='admin',403); $data=$request->validate(['review_note'=>['required','string','max:2000']]); $changeRequest->update(['status'=>'rejected','reviewed_by'=>auth()->id(),'review_note'=>$data['review_note']]); return back()->with('success','Permintaan ditolak.'); }
}