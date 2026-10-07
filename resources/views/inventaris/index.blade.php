@extends('layouts.app')

@section('title', 'Data inventaris')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Data inventaris alat</h1>
            <p class="subtitle">Daftar alat laboratorium yang dapat dipilih atau dipindai untuk inspeksi.</p>
        </div>
        <div class="actions">
            <a class="button" href="{{ route('inventaris.create') }}">Tambah alat</a>
            <a class="button secondary" href="{{ route('inventaris.labels') }}">Cetak label QR</a>
        </div>
    </div>

    <section class="panel">
        <div class="section-head">
            <h2>Inventaris terdaftar</h2>
            <span class="muted">{{ $alat->count() }} alat</span>
        </div>
        @if ($alat->isEmpty())
            <p class="empty">Belum ada alat yang terdaftar di inventaris.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>ID alat / QR</th><th>Nama alat</th><th>Lokasi</th></tr></thead>
                    <tbody>
                        @foreach ($alat as $item)
                            <tr><td><strong>{{ $item->id_alat }}</strong></td><td>{{ $item->nama_alat }}</td><td>{{ $item->lokasi_meja }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
