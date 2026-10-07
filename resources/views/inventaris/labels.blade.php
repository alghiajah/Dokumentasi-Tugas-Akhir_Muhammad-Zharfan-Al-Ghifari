@extends('layouts.app')

@section('title', 'Label QR')

@push('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" defer></script>
@endpush

@section('content')
    <div class="page-heading">
        <div>
            <h1>Label QR inventaris</h1>
            <p class="subtitle">Setiap kode menyimpan ID alat dan dapat dipindai melalui halaman inspeksi.</p>
        </div>
        <button class="button no-print" type="button" onclick="window.print()">Cetak semua label</button>
    </div>

    <div class="alert info no-print qr-warning" hidden>Library QR tidak dapat dimuat. Periksa koneksi internet, lalu muat ulang halaman.</div>

    @if ($alat->isEmpty())
        <section class="panel"><p class="empty">Belum ada alat untuk dibuatkan label QR.</p></section>
    @else
        <section class="qr-grid">
            @foreach ($alat as $item)
                <article class="qr-label">
                    <h3>{{ $item->nama_alat }}</h3>
                    <div class="qr-code" data-qr="{{ $item->id_alat }}" aria-label="Kode QR {{ $item->id_alat }}"></div>
                    <p class="qr-id">{{ $item->id_alat }}</p>
                    <p class="qr-location">{{ $item->lokasi_meja }}</p>
                </article>
            @endforeach
        </section>
    @endif
@endsection

@push('head')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            if (typeof QRCode === 'undefined') {
                document.querySelector('.qr-warning')?.removeAttribute('hidden');
                return;
            }

            document.querySelectorAll('.qr-code').forEach((element) => {
                new QRCode(element, {
                    text: element.dataset.qr,
                    width: 150,
                    height: 150,
                    correctLevel: QRCode.CorrectLevel.M
                });
            });
        });
    </script>
@endpush
