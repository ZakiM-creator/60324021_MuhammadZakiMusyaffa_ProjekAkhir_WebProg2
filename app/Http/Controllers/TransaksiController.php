<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('clear_filter')) {
            session()->forget('filter_transaksi');
            return redirect()->route('transaksi.index');
        }

        if ($request->anyFilled(['status', 'anggota_id', 'buku_id', 'tgl_mulai', 'tgl_selesai'])) {
            session(['filter_transaksi' => $request->all()]);
        } elseif (session()->has('filter_transaksi') && empty($request->all())) {
            return redirect()->route('transaksi.index', session('filter_transaksi'));
        }

        $query = Transaksi::with(['anggota', 'buku']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->anggota_id) {
            $query->where('anggota_id', $request->anggota_id);
        }
        if ($request->buku_id) {
            $query->where('buku_id', $request->buku_id);
        }
        if ($request->tgl_mulai) {
            $query->where('tanggal_pinjam', '>=', $request->tgl_mulai);
        }
        if ($request->tgl_selesai) {
            $query->where('tanggal_pinjam', '<=', $request->tgl_selesai);
        }

        $transaksis = $query->latest()->get();
        $anggotas = Anggota::orderBy('nama')->get();
        $bukus = Buku::orderBy('judul')->get();

        return view('transaksi.index', compact('transaksis', 'anggotas', 'bukus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get only anggota aktif
        $anggotas = Anggota::where('status', 'Aktif')->orderBy('nama')->get();

        // Get only buku yang tersedia (stok > 0)
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('transaksi.create', compact('anggotas', 'bukus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            'anggota_id.required' => 'Anggota wajib dipilih.',
            'buku_id.required' => 'Buku wajib dipilih.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Cek status anggota (harus Aktif)
                $anggota = Anggota::findOrFail($request->anggota_id);
                if ($anggota->status !== 'Aktif') {
                    throw new \Exception('Anggota tersebut tidak aktif!');
                }

                // 2. Cek apakah anggota sedang meminjam buku ini (belum dikembalikan)
                $existingTransaction = Transaksi::where('anggota_id', $request->anggota_id)
                    ->where('buku_id', $request->buku_id)
                    ->where('status', 'Dipinjam')
                    ->first();
                if ($existingTransaction) {
                    throw new \Exception('Anggota ini sedang meminjam buku ini dan belum mengembalikannya!');
                }

                // 3. Batasi peminjaman aktif maksimal 3 buku per anggota
                $borrowedCount = Transaksi::where('anggota_id', $request->anggota_id)
                    ->where('status', 'Dipinjam')
                    ->count();
                if ($borrowedCount >= 3) {
                    throw new \Exception('Anggota telah mencapai batas maksimal peminjaman (maksimal 3 buku)!');
                }

                // 4. Check stok buku
                $buku = Buku::findOrFail($request->buku_id);
                if ($buku->stok <= 0) {
                    throw new \Exception('Stok buku habis!');
                }

                // 2. Generate kode transaksi
                $kodeTransaksi = $this->generateKodeTransaksi();

                // 3. Calculate tanggal kembali (7 hari dari tanggal pinjam)
                $tanggalKembali = Carbon::parse($request->tanggal_pinjam)->addDays(7);

                // 4. Create transaksi
                Transaksi::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'anggota_id' => $request->anggota_id,
                    'buku_id' => $request->buku_id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => 'Dipinjam',
                    'keterangan' => $request->keterangan,
                ]);

                // 5. Update stok buku (kurang 1)
                $buku->decrement('stok');
            });

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi peminjaman berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['anggota', 'buku'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Kembalikan buku (update status transaksi).
     */
    public function kembalikan(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $transaksi = Transaksi::findOrFail($id);

                // Cek apakah sudah dikembalikan
                if ($transaksi->status === 'Dikembalikan') {
                    throw new \Exception('Buku sudah dikembalikan sebelumnya.');
                }

                $tanggalDikembalikan = now();
                $denda = $this->hitungDenda($transaksi, $tanggalDikembalikan);

                $transaksi->update([
                    'status' => 'Dikembalikan',
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'denda' => $denda,
                ]);

                $transaksi->buku->increment('stok');
            });

            return redirect()->route('transaksi.show', $id) ->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back() ->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }



    /**
     * Generate kode transaksi otomatis.
     */
    private function generateKodeTransaksi()
    {
        $lastTransaksi = Transaksi::orderBy('id', 'desc')->first();

        if ($lastTransaksi) {
            $lastNumber = intval(substr($lastTransaksi->kode_transaksi, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'TRX-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung denda keterlambatan.
     */
    private function hitungDenda($transaksi, $tanggalDikembalikan)
    {
        $hariTerlambat = $transaksi->tanggal_kembali->diffInDays($tanggalDikembalikan, false);

        if ($hariTerlambat > 0) {
            // Denda Rp 5.000 per hari
            return $hariTerlambat * 5000;
        }

        return 0;
    }

    /**
     * Menampilkan laporan transaksi dengan filter.
     */
    public function laporan(Request $request)
    {
        $anggotas = Anggota::orderBy('nama')->get();

        $query = Transaksi::with(['buku', 'anggota']);

        // Filter Tanggal Pinjam
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        } elseif ($request->filled('tgl_mulai')) {
            $query->where('tanggal_pinjam', '>=', $request->tgl_mulai);
        } elseif ($request->filled('tgl_selesai')) {
            $query->where('tanggal_pinjam', '<=', $request->tgl_selesai);
        }

        // Filter Status
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Filter Anggota
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        $transaksis = $query->latest()->get();

        $totalTransaksi = $transaksis->count();
        $totalDenda = $transaksis->sum('denda');

        return view('transaksi.laporan', compact(
            'transaksis',
            'anggotas',
            'totalTransaksi',
            'totalDenda'
        ));
    }

    /**
     * Export laporan transaksi ke PDF murni server-side.
     */
    public function exportPdf(Request $request)
    {
        $query = Transaksi::with(['buku', 'anggota']);

        // Filter Tanggal Pinjam
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        } elseif ($request->filled('tgl_mulai')) {
            $query->where('tanggal_pinjam', '>=', $request->tgl_mulai);
        } elseif ($request->filled('tgl_selesai')) {
            $query->where('tanggal_pinjam', '<=', $request->tgl_selesai);
        }

        // Filter Status
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Filter Anggota
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        $transaksis = $query->latest()->get();

        $totalTransaksi = $transaksis->count();
        $totalDenda = $transaksis->sum('denda');
        $totalDipinjam = $transaksis->where('status', 'Dipinjam')->count();
        $totalDikembalikan = $transaksis->where('status', 'Dikembalikan')->count();

        $pdf = Pdf::loadView('transaksi.pdf', compact(
            'transaksis',
            'totalTransaksi',
            'totalDenda',
            'totalDipinjam',
            'totalDikembalikan'
        ));

        // Format nama file: laporan_transaksi_20240101_101500.pdf
        return $pdf->download('laporan_transaksi_' . date('Ymd_His') . '.pdf');
    }
}
