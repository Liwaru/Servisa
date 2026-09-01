<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_active_role_can_login_and_is_redirected_to_its_dashboard(): void
    {
        foreach ([1 => 'pelanggan', 2 => 'mekanik', 3 => 'admin', 4 => 'pemilik'] as $level => $dashboard) {
            $user = User::factory()->create(['username' => 'role'.$level, 'level' => $level]);

            $response = $this->post('/login', ['username' => $user->username, 'password' => 'password']);

            $response->assertRedirect(route('dashboard.'.$dashboard));
            $this->assertAuthenticatedAs($user);
            $this->assertSame($user->id_user, session('id_user'));
            $this->assertSame($user->username, session('username'));
            $this->assertSame($level, session('level'));
            $this->post('/logout')->assertRedirect(route('login'));
            $this->assertGuest();
        }
    }

    public function test_phone_number_cannot_be_used_to_login(): void
    {
        User::factory()->create(['username' => 'pelanggan', 'no_hp' => '081111111111']);

        $this->post('/login', ['username' => '081111111111', 'password' => 'password'])
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_inactive_user_is_rejected(): void
    {
        User::factory()->create(['username' => 'nonaktif', 'status_akun' => 'nonaktif']);
        $this->post('/login', ['username' => 'nonaktif', 'password' => 'password'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_wrong_password_is_rejected(): void
    {
        User::factory()->create(['username' => 'pelanggan']);

        $this->post('/login', ['username' => 'pelanggan', 'password' => 'password-salah'])
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_registration_always_creates_a_customer(): void
    {
        $response = $this->post('/register', [
            'no_hp' => '089999999999', 'username' => 'budi',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHas('success', 'Akun berhasil dibuat. Silakan login.');
        $this->assertDatabaseHas('users', ['username' => 'budi', 'level' => 1, 'status_akun' => 'aktif']);
        $this->assertGuest();
        $this->assertTrue(password_verify('rahasia123', User::where('username', 'budi')->value('password')));
    }

    public function test_registration_rejects_duplicate_username(): void
    {
        User::factory()->create(['username' => 'sudahada']);

        $this->from('/register')->post('/register', [
            'no_hp' => '081234567891', 'username' => 'sudahada',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/register')->assertSessionHasErrors('username');
    }

    public function test_registration_rejects_duplicate_phone_number(): void
    {
        User::factory()->create(['no_hp' => '081234567892']);

        $this->from('/register')->post('/register', [
            'no_hp' => '081234567892', 'username' => 'budiunik',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/register')->assertSessionHasErrors('no_hp');
    }

    public function test_registration_rejects_an_invalid_phone_and_password_confirmation(): void
    {
        $this->from('/register')->post('/register', [
            'no_hp' => '08abc', 'username' => 'budiunik',
            'password' => 'rahasia123', 'password_confirmation' => 'berbeda123',
        ])->assertRedirect('/register')->assertSessionHasErrors(['no_hp', 'password']);
    }

    public function test_register_uses_the_same_brand_panel_as_login(): void
    {
        $this->get('/login')->assertOk()
            ->assertSee('Logo Servisa.png')
            ->assertSee('login-hero.css');

        $this->get('/register')->assertOk()
            ->assertSee('Logo Servisa.png')
            ->assertSee('login-hero.css')
            ->assertSee('Mekanik Panggilan')
            ->assertSee('Daftar ke Servisa');
    }

    public function test_global_servisa_loader_is_available_on_application_pages(): void
    {
        $this->assertFileExists(public_path('animations/wrench-loading.json'));
        $this->assertFileExists(public_path('css/servisa-loader.css'));
        $this->assertFileExists(public_path('js/servisa-loader.js'));
        $this->assertIsArray(json_decode(
            file_get_contents(public_path('animations/wrench-loading.json')),
            true,
            flags: JSON_THROW_ON_ERROR,
        ));

        $this->get('/login')->assertOk()
            ->assertSee('servisa-loader-overlay')
            ->assertSee('animations/wrench-loading.json')
            ->assertSee('js/servisa-loader.js')
            ->assertSee('Memverifikasi akun...');

        $this->get('/register')->assertOk()
            ->assertSee('servisa-loader-overlay')
            ->assertSee('Membuat akun...');

        $user = User::factory()->create(['level' => 1]);
        $this->actingAs($user)->get('/dashboard/pelanggan')->assertOk()
            ->assertSee('servisa-loader-overlay')
            ->assertSee('Keluar dari akun...');
    }

    public function test_database_can_be_seeded_with_servisa_catalog_and_demo_roles(): void
    {
        $this->seed();

        $this->assertDatabaseCount('layanan', 14);
        $this->assertDatabaseCount('sparepart', 15);
        foreach ([1, 2, 3, 4] as $level) {
            $this->assertDatabaseHas('users', ['level' => $level, 'status_akun' => 'aktif']);
        }
    }

    public function test_user_cannot_open_another_roles_dashboard(): void
    {
        $user = User::factory()->create(['level' => 1]);
        $this->actingAs($user)->get('/dashboard/admin')->assertForbidden();
    }
}
