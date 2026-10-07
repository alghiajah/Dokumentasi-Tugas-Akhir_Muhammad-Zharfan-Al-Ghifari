@extends('layouts.app')

@section('title', 'Formulir inspeksi')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Formulir inspeksi harian</h1>
            <p class="subtitle">Catat kondisi aktual alat setelah pemindaian atau pemilihan ID.</p>
        </div>
    </div>

    <section class="form-card">
        <h2>Data alat</h2>
        <div class="panel" style="padding: 15px; box-shadow: none;">
            <p><strong>ID QR:</strong> {{ $alat->id_alat }}</p>
            <p><strong>Nama alat:</strong> {{ $alat->nama_alat }}</p>
            <p><strong>Lokasi:</strong> {{ $alat->lokasi_meja }}</p>
            <p class="muted">Waktu inspeksi dicatat otomatis saat formulir disimpan. Petugas: {{ auth()->user()->nama_asisten }}.</p>
        </div>

        <form method="POST" action="{{ route('inspeksi.store') }}">
            @csrf
            <input type="hidden" name="id_alat" value="{{ $alat->id_alat }}">
            <div class="field">
                <label for="status">Kondisi alat</label>
                <select id="status" name="status" required>
                    <option value="">Pilih kondisi</option>
                    <option value="Lengkap" @selected(old('status') === 'Lengkap')>Lengkap / Baik</option>
                    <option value="Rusak" @selected(old('status') === 'Rusak')>Rusak</option>
                    <option value="Hilang" @selected(old('status') === 'Hilang')>Hilang / Tidak ditemukan</option>
                </select>
            </div>
            <div class="field">
                <label for="catatan">Catatan (opsional)</label>
                <textarea id="catatan" name="catatan" maxlength="2000" placeholder="Contoh: kabel probe perlu diganti">{{ old('catatan') }}</textarea>
            </div>
            <div class="form-actions">
                <button class="button success" type="submit">Simpan hasil inspeksi</button>
                <a class="button secondary" href="{{ route('inspeksi.scan') }}">Kembali ke pemindai</a>
            </div>
        </form>
    </section>
@endsection
