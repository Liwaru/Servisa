<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route($this->dashboardRoute((int) Auth::user()->level));
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'Username atau password tidak sesuai.']);
        }

        if (strtolower((string) $user->status_akun) !== 'aktif') {
            return back()->withInput($request->only('username'))
                ->withErrors(['username' => 'Akun Anda tidak aktif. Silakan hubungi admin Servisa.']);
        }

        if (! in_array((int) $user->level, [1, 2, 3, 4], true)) {
            return back()->withErrors(['username' => 'Level akun tidak dikenali. Silakan hubungi admin Servisa.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->put([
            'id_user' => $user->id_user,
            'username' => $user->username,
            'level' => (int) $user->level,
        ]);

        return redirect()->intended(route($this->dashboardRoute((int) $user->level)));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,11}$/', 'unique:users,no_hp'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Nomor HP harus diawali 08 dan terdiri dari 10–13 angka.',
            'no_hp.unique' => 'Nomor HP sudah terdaftar.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        User::create([
            'no_hp' => $data['no_hp'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'level' => 1,
            'status_akun' => 'aktif',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    private function dashboardRoute(int $level): string
    {
        return match ($level) {
            2 => 'dashboard.mekanik',
            3 => 'dashboard.admin',
            4 => 'dashboard.pemilik',
            default => 'dashboard.pelanggan',
        };
    }
}
