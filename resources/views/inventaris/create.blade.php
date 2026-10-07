@extends('layouts.app')

@section('title', 'Tambah alat')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Tambah inventaris</h1>
            <p class="subtitle">Masukkan identitas alat sesuai label inventaris laboratorium.</p>
        </div>
    </div>

    <section class="form-card">
        <form method="POST" action="{{ route('inventaris.store') }}">
            @csrf
            <div class="field">
                <label for="id_alat">ID alat untuk QR</label>
                <input id="id_alat" name="id_alat" type="text" value="{{ old('id_alat') }}" maxlength="50" placeholder="Contoh: QR-005" required>
            </div>
            <div class="field">
                <label for="nama_alat">Nama alat</label>
                <input id="nama_alat" name="nama_alat" type="text" value="{{ old('nama_alat') }}" maxlength="100" placeholder="Contoh: Tang potong" required>
            </div>
            <div class="field">
                <label for="lokasi_meja">Lokasi penyimpanan</label>
                <input id="lokasi_meja" name="lokasi_meja" type="text" value="{{ old('lokasi_meja') }}" maxlength="50" placeholder="Contoh: Meja praktikum 3" required>
            </div>
            <div class="form-actions">
                <button class="button" type="submit">Simpan alat</button>
                <a class="button secondary" href="{{ route('inventaris.index') }}">Batal</a>
            </div>
        </form>
    </section>
@endsection
