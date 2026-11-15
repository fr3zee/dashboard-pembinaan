<?php
namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Pembinaan;
use App\Models\PembinaanFile;
use Illuminate\Http\Request;

class PembinaanFileCatalogController extends Controller
{
    private function query()
    {
        $query = PembinaanFile::with(['pembinaan.instansi', 'uploader'])
            ->when(! $this->canSeePrivate(), fn ($file) => $file->where('visibilitas', 'Publikasi'));
        return $query;
    }

    private function canSeePrivate(): bool
    {
        return in_array(request()->user()->role, ['admin', 'pelaksana'], true);
    }

    private function visibleFiles($query, string $jenis)
    {
        return $query->where('jenis', $jenis)->when(! $this->canSeePrivate(), fn ($file) => $file->where('visibilitas', 'Publikasi'));
    }

    private function applyFilters($query)
    {
        if (request('q')) {
            $term = request('q');
            $query->where(function ($builder) use ($term) {
                $builder->where('nama_asli', 'like', "%{$term}%")
                    ->orWhere('jenis', 'like', "%{$term}%")
                    ->orWhereHas('pembinaan', fn ($pembinaan) => $pembinaan
                        ->where('nama_kegiatan', 'like', "%{$term}%")
                        ->orWhere('layanan', 'like', "%{$term}%")
                        ->orWhere('lokasi', 'like', "%{$term}%")
                        ->orWhereHas('instansi', fn ($instansi) => $instansi->where('nama', 'like', "%{$term}%")));
            });
        }
        if (request('jenis')) $query->where('jenis', request('jenis'));
        if (request('visibilitas')) $query->where('visibilitas', request('visibilitas'));
        if (request('instansi_id')) $query->whereHas('pembinaan', fn ($builder) => $builder->where('instansi_id', request('instansi_id')));
        return $query;
    }

    private function data($items): array
    {
        return ['items' => $items, 'instansi' => Instansi::orderBy('nama')->get()];
    }

    public function index()
    {
        $this->authorize('viewAny', PembinaanFile::class);
        return view('pembinaan-files.index', $this->data($this->applyFilters($this->query())->latest()->paginate(15)->withQueryString()));
    }

    public function materi()
    {
        $this->authorize('viewAny', PembinaanFile::class);
        $query = Pembinaan::with([
            'instansi',
            'pegawai',
            'files' => fn ($file) => $this->visibleFiles($file, 'materi'),
        ])->whereHas('files', fn ($file) => $this->visibleFiles($file, 'materi'));

        if (request('q')) {
            $term = request('q');
            $query->where(function ($builder) use ($term) {
                $builder->whereHas('instansi', fn ($instansi) => $instansi->where('nama', 'like', "%{$term}%"))
                    ->orWhere('layanan', 'like', "%{$term}%")
                    ->orWhere('lokasi', 'like', "%{$term}%")
                    ->orWhereHas('files', fn ($file) => $this->visibleFiles($file, 'materi')->where('nama_asli', 'like', "%{$term}%"));
            });
        }
        if (request('visibilitas')) $query->whereHas('files', fn ($file) => $this->visibleFiles($file, 'materi')->where('visibilitas', request('visibilitas')));
        if (request('instansi_id')) $query->where('instansi_id', request('instansi_id'));

        return view('pembinaan-files.materi', [
            'items' => $query->latest('tanggal_mulai')->paginate(12)->withQueryString(),
            'instansi' => Instansi::orderBy('nama')->get(),
        ]);
    }

    public function materiShow(Request $request, Pembinaan $pembinaan)
    {
        $this->authorize('view', $pembinaan);

        // Load semua file yang boleh dilihat user (laporan selalu publik, materi sesuai role)
        $item = $pembinaan->load([
            'instansi',
            'pegawai',
            'files' => $this->catalogFileScope(),
        ]);

        $allFiles  = $item->files->sortByDesc('created_at')->values();
        $reports   = $allFiles->where('jenis', 'laporan')->values();
        $materials = $allFiles->where('jenis', 'materi')->values();

        // File yang dipratinjau: query param 'file', atau PDF pertama yang ditemukan
        $selectedFile = $allFiles->firstWhere('id', (int) $request->input('file'));
        if (! $selectedFile) {
            $selectedFile = $allFiles->first(fn ($f) => $f->mime_type === 'application/pdf');
        }

        return view('pembinaan-files.materi-show', compact('item', 'reports', 'materials', 'selectedFile'));
    }
    private function catalogFileScope()
    {
        $internal = $this->canSeePrivate();

        return function ($file) use ($internal) {
            $file->where(function ($visible) use ($internal) {
                $visible->where('jenis', 'laporan');
                if ($internal) $visible->orWhere('jenis', 'materi');
                else $visible->orWhere(fn ($materi) => $materi->where('jenis', 'materi')->where('visibilitas', 'Publikasi'));
            });
        };
    }

    private function catalogQuery()
    {
        $files = $this->catalogFileScope();

        return Pembinaan::with(['instansi', 'pegawai', 'files' => $files])
            ->withExists(['files as private_material_exists' => fn ($file) => $file->where('jenis', 'materi')->where('visibilitas', 'Private')])
            ->whereHas('files');
    }

    public function laporan()
    {
        $this->authorize('viewAny', PembinaanFile::class);
        $query = $this->catalogQuery();
        if (request('q')) {
            $term = request('q');
            $scope = $this->catalogFileScope();
            $query->where(function ($builder) use ($term, $scope) {
                $builder->whereHas('instansi', fn ($i) => $i->where('nama', 'like', "%{$term}%"))
                    ->orWhere('layanan', 'like', "%{$term}%")
                    ->orWhere('lokasi', 'like', "%{$term}%")
                    ->orWhereHas('files', function ($file) use ($scope, $term) {
                        $scope($file);
                        $file->where('nama_asli', 'like', "%{$term}%");
                    });
            });
        }
        if (request('visibilitas')) {
            $scope = $this->catalogFileScope();
            $query->whereHas('files', function ($file) use ($scope) {
                $scope($file);
                $file->where('visibilitas', request('visibilitas'));
            });
        }
        if (request('instansi_id')) $query->where('instansi_id', request('instansi_id'));
        if (request('status_dokumen')) $query->where('status_dokumen', request('status_dokumen'));
        if (request('layanan')) $query->where('layanan', request('layanan'));

        return view('pembinaan-files.laporan', [
            'items' => $query->latest('tanggal_mulai')->paginate(15)->withQueryString(),
            'instansi' => Instansi::orderBy('nama')->get(),
        ]);
    }
}