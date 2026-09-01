<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard {{ $role }} | Servisa</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f5f7fa; color: #1f2937; font-family: Inter, Arial, sans-serif; }
        .card { width: min(90%, 620px); padding: 48px; border-radius: 20px; background: #fff; box-shadow: 0 20px 60px rgba(15, 76, 129, .1); text-align: center; }
        .badge { display: inline-block; padding: 8px 13px; border-radius: 99px; color: #0f4c81; background: #eaf3ff; font-weight: 700; }
        .card h1 { margin: 20px 0 10px; }
        .card p { color: #6b7280; }
        .card button { margin-top: 20px; padding: 13px 26px; border: 0; border-radius: 9px; background: #2f80ed; color: #fff; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
<main class="card">
    <span class="badge">{{ $role }}</span>
    <h1>Halo, {{ session('username') }}!</h1>
    <p>Anda berhasil masuk ke dashboard {{ strtolower($role) }} Servisa.</p>
    <form method="POST" action="{{ route('logout') }}" data-servisa-loader-text="Keluar dari akun...">
        @csrf
        <button type="submit">Logout</button>
    </form>
</main>
<x-servisa-loader overlay text="Memuat data..." />
</body>
</html>
