@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="login-wrap">
        <section class="login-card">
            <div class="login-mark" aria-hidden="true">IL</div>
            <h1>Masuk petugas</h1>
            <p class="subtitle">Gunakan akun asisten laboratorium yang telah terdaftar.</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Kata sandi</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                </div>
                <button class="button" type="submit">Masuk ke aplikasi</button>
            </form>
        </section>
    </div>
@endsection
