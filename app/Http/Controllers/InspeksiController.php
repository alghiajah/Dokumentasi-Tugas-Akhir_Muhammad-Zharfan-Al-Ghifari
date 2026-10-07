<?php

namespace App\Http\Controllers;

use App\Models\AlatInventaris;
use App\Models\LogInspeksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InspeksiController extends Controller
{
    public function scan(): View
    {
        return view('inspeksi.scan', [
            'alat' => AlatInventaris::query()->orderBy('id_alat')->get(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $data = $request->validate([
            'id_alat' => ['required', 'string', 'max:50'],
        ], [
            'id_alat.required' => 'Pindai QR atau masukkan ID alat terlebih dahulu.',
            'id_alat.max' => 'ID alat maksimal 50 karakter.',
        ]);

        $alat = AlatInventaris::query()->find($data['id_alat']);

        if (! $alat) {
            return redirect()
                ->route('inspeksi.scan')
                ->withErrors(['id_alat' => 'ID QR tidak terdaftar. Periksa kode atau pilih alat dari daftar.']);
        }

        return view('inspeksi.create', ['alat' => $alat]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_alat' => ['required', 'string', 'exists:alat_inventaris,id_alat'],
            'status' => ['required', 'in:Lengkap,Rusak,Hilang'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'id_alat.required' => 'ID alat tidak ditemukan. Silakan pindai ulang.',
            'id_alat.exists' => 'Alat tidak lagi terdaftar di inventaris.',
            'status.required' => 'Pilih kondisi alat sebelum menyimpan.',
            'status.in' => 'Kondisi alat yang dipilih tidak valid.',
            'catatan.max' => 'Catatan maksimal 2.000 karakter.',
        ]);

        $data['username'] = $request->user()->username;
        LogInspeksi::query()->create($data);

        return redirect()->route('dashboard')->with('status', 'Hasil inspeksi berhasil disimpan.');
    }
}
