@extends('layouts.app')

@section('title', 'Pemindaian QR')

@push('head')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" defer></script>
@endpush

@section('content')
    <div class="page-heading">
        <div>
            <h1>Mulai inspeksi</h1>
            <p class="subtitle">Pindai label QR alat atau masukkan ID secara manual.</p>
        </div>
    </div>

    <section class="panel scan-stage">
        <h2>Pindai kode QR alat</h2>
        <p>Izinkan akses kamera browser untuk mulai memindai. Kamera memerlukan HTTPS atau localhost.</p>
        <div id="reader"></div>
        <p id="scanner-message" class="muted" role="status"></p>
    </section>

    <section class="form-card">
        <h2>Pilih alat secara manual</h2>
        @if ($alat->isEmpty())
            <p class="empty">Belum ada alat dalam inventaris. Tambahkan alat terlebih dahulu.</p>
            <a class="button" href="{{ route('inventaris.create') }}">Tambah alat</a>
        @else
            <form id="pilih-alat-form" method="POST" action="{{ route('inspeksi.create') }}">
                @csrf
                <div class="field">
                    <label for="id_alat">ID alat</label>
                    <input id="id_alat" name="id_alat" type="text" list="daftar-alat" maxlength="50" placeholder="Pindai QR atau ketik ID alat" required>
                    <datalist id="daftar-alat">
                        @foreach ($alat as $item)
                            <option value="{{ $item->id_alat }}">{{ $item->nama_alat }} — {{ $item->lokasi_meja }}</option>
                        @endforeach
                    </datalist>
                </div>
                <button class="button" type="submit">Lanjut ke formulir inspeksi</button>
            </form>
        @endif
    </section>
@endsection

@push('head')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const message = document.getElementById('scanner-message');
            const form = document.getElementById('pilih-alat-form');

            if (typeof Html5QrcodeScanner === 'undefined') {
                message.textContent = 'Library pemindai tidak dapat dimuat. Gunakan pemilihan alat secara manual.';
                return;
            }

            if (!form) {
                message.textContent = 'Tambahkan inventaris alat sebelum memulai inspeksi.';
                return;
            }

            const scanner = new Html5QrcodeScanner('reader', {
                fps: 10,
                qrbox: { width: 240, height: 240 },
                rememberLastUsedCamera: true
            }, false);

            scanner.render((hasil) => {
                document.getElementById('id_alat').value = hasil.trim();
                message.textContent = 'QR terbaca. Membuka formulir inspeksi…';
                form.requestSubmit();
            }, () => {});
        });
    </script>
@endpush
