<?php
namespace App\Http\Controllers;
use App\Models\Pembinaan;
use Illuminate\Support\Carbon;
class RekapController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Pembinaan::class);
        $user = request()->user();
        $base = Pembinaan::query()->with(['instansi', 'pegawai']);
        $years = (clone $base)->selectRaw('YEAR(tanggal_mulai) as year')->distinct()->orderByDesc('year')->pluck('year');
        $year = request('tahun');
        if ($year !== null && in_array((int) $year, $years->map(fn ($v) => (int) $v)->all(), true)) $base->whereYear('tanggal_mulai', (int) $year); else $year = $years->first();
        $quarter = request('triwulan');
        if (in_array($quarter, ['1','2','3','4'], true) && $year) {
            $start = Carbon::create((int) $year, ((int) $quarter - 1) * 3 + 1, 1);
            $base->whereBetween('tanggal_mulai', [$start->toDateString(), $start->copy()->addMonths(3)->subDay()->toDateString()]);
        }
        $total = (clone $base)->count();
        $instansi = (clone $base)->distinct()->count('instansi_id');
        $ids = (clone $base)->select('pembinaan.id');
        $narasumber = \DB::table('pembinaan_pegawai')->whereIn('pembinaan_id', $ids)->distinct()->count('pegawai_id');
        $periodCounts = collect(range(1, 4))->mapWithKeys(function (int $period) use ($base): array { return [$period => (clone $base)->whereMonth('tanggal_mulai', '>=', (($period - 1) * 3) + 1)->whereMonth('tanggal_mulai', '<=', $period * 3)->count()]; });
        $serviceNames = ['Sosialisasi Srikandi', 'Integrasi', 'Pendampingan Teknis', 'Troubleshooting', 'Audit/Evaluasi', 'Bimbingan Teknis'];
        $serviceTotals = (clone $base)
            ->selectRaw("CASE
                WHEN TRIM(layanan) IN ('Bimtek', 'Bimbingan Teknis') THEN 'Bimbingan Teknis'
                WHEN TRIM(layanan) IN ('Sosialisasi/Bimtek', 'Sosialisasi dan Bimtek') THEN 'Sosialisasi Srikandi'
                ELSE TRIM(layanan)
            END AS layanan, COUNT(*) AS jumlah")
            ->groupByRaw("CASE
                WHEN TRIM(layanan) IN ('Bimtek', 'Bimbingan Teknis') THEN 'Bimbingan Teknis'
                WHEN TRIM(layanan) IN ('Sosialisasi/Bimtek', 'Sosialisasi dan Bimtek') THEN 'Sosialisasi Srikandi'
                ELSE TRIM(layanan)
            END")
            ->pluck('jumlah', 'layanan');
        $serviceCounts = collect($serviceNames)->map(fn (string $name) => (object) ['layanan' => $name, 'jumlah' => (int) ($serviceTotals[$name] ?? 0)]);
        $items = (clone $base)->latest('tanggal_mulai')->paginate(15)->withQueryString();
        $instansiRows = (clone $base)->selectRaw('instansi_id, COUNT(*) jumlah, MAX(tanggal_mulai) terakhir')->groupBy('instansi_id')->with('instansi')->get();
        $narasumberRows = (clone $base)->join('pembinaan_pegawai', 'pembinaan.id', '=', 'pembinaan_pegawai.pembinaan_id')->join('pegawai', 'pegawai.id', '=', 'pembinaan_pegawai.pegawai_id')->selectRaw('pegawai.id, pegawai.nama_lengkap, COUNT(DISTINCT pembinaan.id) jumlah, MAX(pembinaan.tanggal_mulai) terakhir')->groupBy('pegawai.id', 'pegawai.nama_lengkap')->orderByDesc('jumlah')->get();
        return view('rekap.index', compact('years','year','quarter','items','total','instansi','narasumber','instansiRows','narasumberRows','periodCounts','serviceCounts'));
    }
}
