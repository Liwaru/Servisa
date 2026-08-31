<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0F4C81">
    <title>Daftar | Servisa</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/password-toggle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
<main class="login-layout register-page">
    @include('auth.partials.brand-panel')

    <section class="form-side">
        <div class="login-card register-card">
            <header class="card-heading">
                <h1>Daftar ke Servisa</h1>
                <p>Buat akun pelanggan untuk mulai memesan layanan.</p>
            </header>

            <form method="POST" action="{{ route('register.store') }}" class="login-form register-form" novalidate>
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrap @error('username') has-error @enderror">
                        <span class="input-icon">{!! view('components.icon', ['name' => 'user']) !!}</span>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="Pilih username" autocomplete="username" required>
                    </div>
                    @error('username')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <div class="input-wrap @error('no_hp') has-error @enderror">
                        <span class="input-icon">{!! view('components.icon', ['name' => 'phone']) !!}</span>
                        <input id="no_hp" name="no_hp" type="tel" inputmode="numeric" pattern="08[0-9]{8,11}" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" autocomplete="tel" required>
                    </div>
                    @error('no_hp')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap @error('password') has-error @enderror">
                        <span class="input-icon">{!! view('components.icon', ['name' => 'lock']) !!}</span>
                        <input id="password" name="password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" aria-label="Tampilkan password" aria-pressed="false" data-password-toggle="password">
                            <span class="toggle-eye-open">{!! view('components.icon', ['name' => 'eye']) !!}</span>
                            <span class="toggle-eye-closed">{!! view('components.icon', ['name' => 'eye-off']) !!}</span>
                        </button>
                    </div>
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">{!! view('components.icon', ['name' => 'lock']) !!}</span>
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" aria-label="Tampilkan konfirmasi password" aria-pressed="false" data-password-toggle="password_confirmation">
                            <span class="toggle-eye-open">{!! view('components.icon', ['name' => 'eye']) !!}</span>
                            <span class="toggle-eye-closed">{!! view('components.icon', ['name' => 'eye-off']) !!}</span>
                        </button>
                    </div>
                </div>

                <button class="login-button" type="submit">Daftar</button>
            </form>
            <p class="register-copy">Sudah punya akun? <a href="{{ route('login') }}">Login sekarang</a></p>
        </div>
    </section>
</main>
<script>
    document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
        const input = document.getElementById(toggle.dataset.passwordToggle);
        toggle.addEventListener('click', () => {
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            toggle.setAttribute('aria-pressed', String(!visible));
            toggle.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
            input.focus();
        });
    });
</script>
</body>
</html>
