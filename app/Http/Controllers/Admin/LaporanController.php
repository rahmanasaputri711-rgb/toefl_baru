<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PercobaanTes;
use App\Models\SesiTes;
use App\Models\PendaftaranTes;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $sesiList = SesiTes::orderByDesc('waktu_mulai')->get();
        $sesi_id  = $request->sesi_id ?? $sesiList->first()?->id;
        $sesi     = $sesi_id ? SesiTes::find($sesi_id) : null;

        $percobaan = collect();
        $stats     = [];
        $distribusi= [];

        if ($sesi_id) {
            $percobaan = PercobaanTes::with(['user','pelanggaran'])
                ->where('sesi_id', $sesi_id)
                ->whereIn('status', ['selesai','dibatalkan'])
                ->orderByDesc('skor_total')
                ->paginate(25)->withQueryString();

            $all = PercobaanTes::where('sesi_id',$sesi_id)->where('status','selesai');
            $allGet = $all->get();

            $stats = [
                'total_selesai'  => $allGet->count(),
                'rata_total'     => round($allGet->avg('skor_total'), 1),
                'tertinggi'      => $allGet->max('skor_total'),
                'terendah'       => $allGet->min('skor_total'),
                'rata_listening' => round($allGet->avg('skor_listening'),1),
                'rata_structure' => round($allGet->avg('skor_structure'),1),
                'rata_reading'   => round($allGet->avg('skor_reading'),1),
                'lulus'          => $allGet->where('skor_total','>=',500)->count(),
                'tidak_lulus'    => $allGet->where('skor_total','<',500)->count(),
                'curang'         => PercobaanTes::where('sesi_id',$sesi_id)->where('status_curang',1)->count(),
                'total_daftar'   => PendaftaranTes::where('sesi_id',$sesi_id)->count(),
                'hadir'          => PendaftaranTes::where('sesi_id',$sesi_id)->where('is_hadir',true)->count(),
                'absen'          => PendaftaranTes::where('sesi_id',$sesi_id)->where('is_hadir',false)->count(),
            ];

            // Distribusi skor: kelompok per 50 poin (310-677)
            $distribusi = [
                '310-350' => $allGet->whereBetween('skor_total',[310,350])->count(),
                '351-400' => $allGet->whereBetween('skor_total',[351,400])->count(),
                '401-450' => $allGet->whereBetween('skor_total',[401,450])->count(),
                '451-500' => $allGet->whereBetween('skor_total',[451,500])->count(),
                '501-550' => $allGet->whereBetween('skor_total',[501,550])->count(),
                '551-600' => $allGet->whereBetween('skor_total',[551,600])->count(),
                '601-677' => $allGet->whereBetween('skor_total',[601,677])->count(),
            ];
        }

        return view('admin.laporan.index', compact('sesiList','sesi_id','sesi','percobaan','stats','distribusi'));
    }

    public function cariNomor(Request $request)
    {
        $request->validate(['nomor_pendaftaran' => 'required|string']);
        $p = PendaftaranTes::with(['user','sesiTes'])
            ->where('nomor_pendaftaran', trim($request->nomor_pendaftaran))->first();

        if (!$p) return back()->with('error','Nomor pendaftaran tidak ditemukan.');

        $percobaan = PercobaanTes::where('user_id',$p->user_id)
            ->where('sesi_id',$p->sesi_id)->where('status','selesai')->first();

        return view('admin.laporan.cari', compact('p','percobaan'));
    }

    /** Export Excel — generate CSV sederhana */
    public function exportExcel(Request $request, $sesiId)
    {
        $sesi      = SesiTes::findOrFail($sesiId);
        $percobaan = PercobaanTes::with('user')
            ->where('sesi_id', $sesiId)->where('status','selesai')
            ->orderByDesc('skor_total')->get();

        $filename = 'laporan_' . str_replace(' ','_',$sesi->judul) . '_' . date('Ymd') . '.csv';
        $headers  = ['Content-Type'=>'text/csv','Content-Disposition'=>"attachment; filename=\"{$filename}\""];

        $callback = function() use ($percobaan) {
            $f = fopen('php://output','w');
            fputcsv($f, ['No','Nama','Email','Skor Total','Listening','Structure','Reading','Status Curang','Waktu Selesai']);
            foreach ($percobaan as $i => $p) {
                fputcsv($f, [
                    $i+1, $p->user?->name, $p->user?->email,
                    $p->skor_total, $p->skor_listening, $p->skor_structure, $p->skor_reading,
                    $p->status_curang ? 'Ya' : 'Tidak',
                    $p->waktu_selesai?->format('d/m/Y H:i'),
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}
