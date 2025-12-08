<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WarehouseDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // ambil filter dari query string (GET)
        $threshold = (int) $request->query('threshold', 20); // default 20

        // date range filter (format YYYY-MM-DD dari input type=date)
        $dateStartRaw = $request->query('date_start');
        $dateEndRaw = $request->query('date_end');

        $dateStart = null;
        $dateEnd = null;
        try {
            if ($dateStartRaw) {
                $dateStart = Carbon::parse($dateStartRaw)->startOfDay();
            }
            if ($dateEndRaw) {
                $dateEnd = Carbon::parse($dateEndRaw)->endOfDay();
            }
        } catch (\Exception $e) {
            // ignore parse errors -> treat as no date filter
            $dateStart = null;
            $dateEnd = null;
        }

        // query dasar untuk barang stok rendah (status 'masuk' + stok < threshold)
        $baseLowQuery = Barang::where('status_barang', 'masuk')
            ->where('stok', '<', $threshold);

        $data = [
            'totalBarang' => Barang::count(),
            'totalStok' => Barang::sum('stok'),
            // ambil 4 terendah untuk card
            'lowStockItems' => (clone $baseLowQuery)->orderBy('stok', 'asc')->take(4)->get(),
            // semua untuk tabel
            'allLowStockItems' => (clone $baseLowQuery)->orderBy('stok', 'asc')->get(),
        ];

        // recent inbound: prioritas date range jika ada, kalau tidak ambil semua (atau bisa pakai periode)
        $recentQuery = BarangHistory::where('new_status', 'masuk');
        if ($dateStart && $dateEnd) {
            $recentQuery->whereBetween('changed_at', [$dateStart, $dateEnd]);
        }
        // jika ingin fallback periode (today/month/year) tambahkan logika di sini

        // eager load submitter (form)
        $data['recentInbound'] = $recentQuery->with('formUser')->latest('changed_at')->take(5)->get();

        // recent outbound: ambil dari history dengan new_status 'keluar' (filter date range sama seperti inbound)
        $recentOutboundQuery = BarangHistory::where('new_status', 'keluar');
        if ($dateStart && $dateEnd) {
            $recentOutboundQuery->whereBetween('changed_at', [$dateStart, $dateEnd]);
        }
        $data['recentOutbound'] = $recentOutboundQuery->with('formUser')->latest('changed_at')->take(5)->get();

        // barangMasukToday / atau jumlah masuk di rentang tanggal
        if ($dateStart && $dateEnd) {
            $data['barangMasukToday'] = BarangHistory::where('new_status', 'masuk')
                ->whereBetween('changed_at', [$dateStart, $dateEnd])
                ->count();
        } else {
            $data['barangMasukToday'] = BarangHistory::where('new_status', 'masuk')
                ->whereDate('changed_at', now())
                ->count();
        }

        // barangKeluarToday / jumlah keluar di rentang tanggal (atau hari ini jika tidak ada filter)
        if ($dateStart && $dateEnd) {
            $data['barangKeluarToday'] = BarangHistory::where('new_status', 'keluar')
                ->whereBetween('changed_at', [$dateStart, $dateEnd])
                ->count();
        } else {
            $data['barangKeluarToday'] = BarangHistory::where('new_status', 'keluar')
                ->whereDate('changed_at', now())
                ->count();
        }

        // prepare chart data
        // Inventory Movement Chart (IMC) - monthly counts (masuk / keluar) for selected year
        $year = $dateStart ? $dateStart->year : now()->year;
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $imcMasuk = [];
        $imcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $imcMasuk[] = BarangHistory::where('new_status', 'masuk')
                ->whereYear('changed_at', $year)
                ->whereMonth('changed_at', $m)
                ->count();
            $imcKeluar[] = BarangHistory::where('new_status', 'keluar')
                ->whereYear('changed_at', $year)
                ->whereMonth('changed_at', $m)
                ->count();
        }

        // Stock Value Chart (SVC) - top items by stock (hanya status 'masuk')
        $topItems = Barang::where('status_barang', 'masuk')
            ->orderByDesc('stok')
            ->take(8)
            ->get();
        $svcLabels = $topItems->pluck('nama_barang')->map(fn($v) => $v ?? '-')->toArray();
        $svcData = $topItems->pluck('stok')->toArray();

        $data['imc_labels'] = $months;
        $data['imc_masuk'] = $imcMasuk;
        $data['imc_keluar'] = $imcKeluar;
        $data['svc_labels'] = $svcLabels;
        $data['svc_data'] = $svcData;

        // kirim juga nilai filter supaya view bisa menandai selected option
        $data['selectedThreshold'] = $threshold;
        $data['selectedDateStart'] = $dateStartRaw;
        $data['selectedDateEnd'] = $dateEndRaw;

        return view('dashboard.warehouse.index', $data);
    }
}
