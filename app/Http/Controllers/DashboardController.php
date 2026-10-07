<?php

namespace App\Http\Controllers;

use App\Models\AlatInventaris;
use App\Models\LogInspeksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate([
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'status' => ['nullable', 'in:Semua,Lengkap,Rusak,Hilang'],
        ], [
            'tanggal.date_format' => 'Tanggal inspeksi harus menggunakan format yang benar.',
            'status.in' => 'Filter kondisi inspeksi tidak valid.',
        ]);

        $tanggal = $filters['tanggal'] ?? now()->toDateString();
        $status = $filters['status'] ?? 'Semua';

        $riwayat = LogInspeksi::query()
            ->with(['alat', 'asisten'])
            ->whereDate('waktu_inspeksi', $tanggal)
            ->orderByDesc('waktu_inspeksi')
            ->orderByDesc('id_log')
            ->get();

        $terakhirPerAlat = $riwayat->unique('id_alat');
        $idAlatDiperiksa = $terakhirPerAlat->pluck('id_alat');
        $totalAlat = AlatInventaris::query()->count();
        $sudahDiperiksa = $idAlatDiperiksa->count();
        $belumDiperiksa = AlatInventaris::query()
            ->whereNotIn('id_alat', $idAlatDiperiksa->all())
            ->orderBy('id_alat')
            ->get();
        $alatBermasalah = $terakhirPerAlat
            ->whereIn('status', ['Rusak', 'Hilang'])
            ->count();
        $cakupan = $totalAlat > 0 ? (int) round(($sudahDiperiksa / $totalAlat) * 100) : 0;

        if ($status !== 'Semua') {
            $riwayat = $riwayat->where('status', $status);
        }

        return view('dashboard', [
            'tanggal' => $tanggal,
            'status' => $status,
            'totalAlat' => $totalAlat,
            'sudahDiperiksa' => $sudahDiperiksa,
            'belumDiperiksa' => $belumDiperiksa,
            'alatBermasalah' => $alatBermasalah,
            'cakupan' => $cakupan,
            'riwayat' => $riwayat,
        ]);
    }
}
