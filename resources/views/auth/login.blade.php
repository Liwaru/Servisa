<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0F4C81">
    <title>Login | Servisa</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/password-toggle.css') }}">
</head>
<body>
<main class="login-layout">
    @include('auth.partials.brand-panel')

    <section class="form-side">
        <div class="login-card">
            <header class="card-heading">
                <span class="eyebrow">SERVISA ACCOUNT</span>
                <h1>Selamat Datang Kembali!</h1>
                <p>Login untuk melanjutkan ke akun Anda</p>
            </header>

            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Login belum berhasil.</strong>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="login-form" data-servisa-loader-text="Memverifikasi akun...">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrap @error('username') has-error @enderror">
                        <span class="input-icon">{!! view('components.icon', ['name' => 'user']) !!}</span>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="Masukkan username" autocomplete="username" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap @error('password') has-error @enderror">
                        <span class="input-icon">{!! view('components.icon', ['name' => 'lock']) !!}</span>
                        <input id="password" name="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required>
                        <button class="password-toggle" type="button" aria-label="Tampilkan password" aria-pressed="false" data-password-toggle>
                            <span class="toggle-eye-open">{!! view('components.icon', ['name' => 'eye']) !!}</span>
                            <span class="toggle-eye-closed">{!! view('components.icon', ['name' => 'eye-off']) !!}</span>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))><span>Ingat saya</span></label>
                </div>
                <button type="submit" class="login-button">Login <span aria-hidden="true"></span></button>
            </form>

            <p class="register-copy">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
        </div>
    </section>
</main>
<x-servisa-loader overlay text="Memverifikasi akun..." />
<script>
    const toggle = document.querySelector('[data-password-toggle]');
    const password = document.getElementById('password');
    toggle.addEventListener('click', () => {
        const visible = password.type === 'text';
        password.type = visible ? 'password' : 'text';
        toggle.setAttribute('aria-pressed', String(!visible));
        toggle.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
        password.focus();
    });
</script>
</body>
</html>
