<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePembinaanFileRequest;
use App\Models\Pembinaan;
use App\Models\PembinaanFile;
use Illuminate\Support\Facades\Storage;

class PembinaanFileController extends Controller
{
    public function store(StorePembinaanFileRequest $request, Pembinaan $pembinaan)
    {
        $file = $request->file('file');
        $path = $file->store('pembinaan/'.$pembinaan->id, 'local');
        $pembinaan->files()->create([
            'uploaded_by' => $request->user()->id,
            'jenis' => $request->validated('jenis'),
            'nama_asli' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'ukuran' => $file->getSize(),
            'visibilitas' => $request->validated('visibilitas'),
        ]);
        return back()->with('success', 'File berhasil diunggah.');
    }

    public function preview(PembinaanFile $pembinaanFile)
    {
        $this->authorize('view', $pembinaanFile);
        abort_unless(Storage::disk('local')->exists($pembinaanFile->path), 404);
        abort_unless($pembinaanFile->mime_type === 'application/pdf', 404, 'Preview hanya tersedia untuk PDF.');
        return response()->file(Storage::disk('local')->path($pembinaanFile->path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.addslashes($pembinaanFile->nama_asli).'"',
        ]);
    }

    public function download(PembinaanFile $pembinaanFile)
    {
        $this->authorize('view', $pembinaanFile);
        abort_unless(Storage::disk('local')->exists($pembinaanFile->path), 404);
        return Storage::disk('local')->download($pembinaanFile->path, $pembinaanFile->nama_asli, ['Content-Type' => $pembinaanFile->mime_type]);
    }

    public function destroy(PembinaanFile $pembinaanFile)
    {
        $this->authorize('delete', $pembinaanFile);
        Storage::disk('local')->delete($pembinaanFile->path);
        $pembinaanFile->delete();
        return back()->with('success', 'File berhasil dihapus.');
    }
}