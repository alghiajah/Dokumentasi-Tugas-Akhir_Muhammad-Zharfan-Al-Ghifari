@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Dashboard inspeksi</h1>
            <p class="subtitle">Ringkasan pemeriksaan inventaris dan kondisi alat laboratorium.</p>
        </div>
        <div class="actions">
            <a class="button success" href="{{ route('inspeksi.scan') }}">Mulai inspeksi</a>
            <a class="button secondary" href="{{ route('inventaris.labels') }}">Cetak label QR</a>
        </div>
    </div>

    <section class="panel">
        <form class="filter-form" method="GET" action="{{ route('dashboard') }}">
            <div class="field">
                <label for="tanggal">Tanggal inspeksi</label>
                <input id="tanggal" name="tanggal" type="date" value="{{ $tanggal }}">
            </div>
            <div class="field">
                <label for="status">Filter riwayat</label>
                <select id="status" name="status">
                    @foreach (['Semua', 'Lengkap', 'Rusak', 'Hilang'] as $pilihan)
                        <option value="{{ $pilihan }}" @selected($status === $pilihan)>{{ $pilihan }}</option>
                    @endforeach
                </select>
            </div>
            <button class="button" type="submit">Tampilkan</button>
        </form>
    </section>

    <section class="metrics" aria-label="Ringkasan pemeriksaan">
        <article class="metric">
            <div class="metric-label">Total inventaris</div>
            <div class="metric-value">{{ $totalAlat }}</div>
            <div class="metric-note">Alat yang terdaftar</div>
        </article>
        <article class="metric">
            <div class="metric-label">Sudah diperiksa</div>
            <div class="metric-value">{{ $sudahDiperiksa }}</div>
            <div class="metric-note">Alat unik pada tanggal terpilih</div>
        </article>
        <article class="metric">
            <div class="metric-label">Belum diperiksa</div>
            <div class="metric-value">{{ $belumDiperiksa->count() }}</div>
            <div class="metric-note">Belum memiliki log pada tanggal ini</div>
        </article>
        <article class="metric">
            <div class="metric-label">Perlu perhatian</div>
            <div class="metric-value">{{ $alatBermasalah }}</div>
            <div class="metric-note">Kondisi terakhir rusak atau hilang</div>
        </article>
    </section>

    <section class="panel">
        <div class="section-head">
            <h2>Cakupan inspeksi</h2>
            <strong>{{ $cakupan }}%</strong>
        </div>
        <p class="muted">{{ $sudahDiperiksa }} dari {{ $totalAlat }} alat telah diperiksa pada {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}.</p>
        <div class="progress" role="progressbar" aria-label="Cakupan inspeksi" aria-valuenow="{{ $cakupan }}" aria-valuemin="0" aria-valuemax="100">
            <span style="width: {{ $cakupan }}%"></span>
        </div>
    </section>

    <section class="panel">
        <div class="section-head">
            <h2>Alat belum diperiksa</h2>
            <span class="muted">{{ $belumDiperiksa->count() }} alat</span>
        </div>
        @if ($belumDiperiksa->isEmpty())
            <p class="empty">Semua alat sudah diperiksa pada tanggal ini.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>ID alat</th><th>Nama alat</th><th>Lokasi</th></tr></thead>
                    <tbody>
                        @foreach ($belumDiperiksa as $alat)
                            <tr><td><strong>{{ $alat->id_alat }}</strong></td><td>{{ $alat->nama_alat }}</td><td>{{ $alat->lokasi_meja }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="panel">
        <div class="section-head">
            <h2>Riwayat pemeriksaan</h2>
            <span class="muted">{{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</span>
        </div>
        @if ($riwayat->isEmpty())
            <p class="empty">Belum ada catatan inspeksi yang cocok dengan tanggal dan filter.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Waktu</th><th>ID alat</th><th>Nama alat</th><th>Lokasi</th><th>Petugas</th><th>Status</th><th>Catatan</th></tr></thead>
                    <tbody>
                        @foreach ($riwayat as $log)
                            <tr>
                                <td>{{ $log->waktu_inspeksi?->format('H:i') ?? '-' }}</td>
                                <td><strong>{{ $log->id_alat }}</strong></td>
                                <td>{{ $log->alat?->nama_alat ?? 'Alat tidak ditemukan' }}</td>
                                <td>{{ $log->alat?->lokasi_meja ?? '-' }}</td>
                                <td>{{ $log->asisten?->nama_asisten ?? 'Tidak diketahui' }}</td>
                                <td><span class="badge {{ strtolower($log->status) }}">{{ $log->status === 'Lengkap' ? 'Lengkap / Baik' : $log->status }}</span></td>
                                <td>{{ $log->catatan ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
