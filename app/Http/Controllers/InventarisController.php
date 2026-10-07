<?php

namespace App\Http\Controllers;

use App\Models\AlatInventaris;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InventarisController extends Controller
{
    public function index(): View
    {
        return view('inventaris.index', [
            'alat' => AlatInventaris::query()->orderBy('id_alat')->get(),
        ]);
    }

    public function create(): View
    {
        return view('inventaris.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_alat' => ['required', 'string', 'max:50', Rule::unique('alat_inventaris', 'id_alat')],
            'nama_alat' => ['required', 'string', 'max:100'],
            'lokasi_meja' => ['required', 'string', 'max:50'],
        ], [
            'id_alat.required' => 'ID alat wajib diisi.',
            'id_alat.max' => 'ID alat maksimal 50 karakter.',
            'id_alat.unique' => 'ID alat tersebut sudah terdaftar.',
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'nama_alat.max' => 'Nama alat maksimal 100 karakter.',
            'lokasi_meja.required' => 'Lokasi alat wajib diisi.',
            'lokasi_meja.max' => 'Lokasi alat maksimal 50 karakter.',
        ]);

        AlatInventaris::query()->create($data);

        return redirect()->route('inventaris.index')->with('status', 'Data alat berhasil ditambahkan.');
    }

    public function labels(): View
    {
        return view('inventaris.labels', [
            'alat' => AlatInventaris::query()->orderBy('id_alat')->get(),
        ]);
    }
}
