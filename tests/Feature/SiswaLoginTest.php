<?php

use App\Models\Exam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function buatSiswa(array $overrides = []): User
{
    return User::factory()->create(array_merge([
        'name' => 'Siswa Uji',
        'email' => null,
        'nisn' => '1234567890',
        'password' => 'rahasia123',
        'is_staff' => false,
    ], $overrides));
}

it('menampilkan halaman login siswa', function () {
    $this->get(route('siswa.login'))
        ->assertOk()
        ->assertSee('Masuk ke Akun', false)
        ->assertSee('Selamat Datang', false)
        ->assertSee('Masukkan NISN', false)
        ->assertSee('Masukkan kata sandi', false)
        ->assertSee('images/illustrations/ilustrasi-siswa-laptop-4k-tanpa-latar.svg', false)
        ->assertDontSee('Daftar sekarang', false);
});

it('login sekali submit langsung bisa membuka dashboard (cookie round-trip)', function () {
    buatSiswa();

    $this->post(route('siswa.login.store'), [
        'nisn' => '1234567890',
        'password' => 'rahasia123',
    ])->assertRedirect(route('siswa.dashboard'));

    // Ikuti redirect dengan cookie sesi yang sama: harus 200, bukan balik ke login.
    $this->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('Halo, Siswa!', false);
});

it('logout lalu login lagi cukup sekali submit', function () {
    config()->set('session.driver', 'database');

    buatSiswa();

    $this->post(route('siswa.login.store'), [
        'nisn' => '1234567890',
        'password' => 'rahasia123',
    ])->assertRedirect(route('siswa.dashboard'));

    $this->get(route('siswa.dashboard'))->assertOk();

    $this->post(route('siswa.logout'))->assertRedirect(route('siswa.login'));
    $this->assertGuest();

    $this->post(route('siswa.login.store'), [
        'nisn' => '1234567890',
        'password' => 'rahasia123',
    ])->assertRedirect(route('siswa.dashboard'));

    $this->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('Halo, Siswa!', false);
});

it('siswa bisa login dengan NISN 10 digit dan password benar', function () {
    buatSiswa();

    $this->post(route('siswa.login.store'), [
        'nisn' => '1234567890',
        'password' => 'rahasia123',
    ])->assertRedirect(route('siswa.dashboard'));

    $this->assertAuthenticated();
});

it('menolak password yang salah', function () {
    buatSiswa();

    $this->post(route('siswa.login.store'), [
        'nisn' => '1234567890',
        'password' => 'salah-password',
    ])->assertRedirect(route('siswa.login'));

    $this->assertGuest();
});

it('memvalidasi NISN harus 10 digit', function () {
    $this->post(route('siswa.login.store'), [
        'nisn' => '12345',
        'password' => 'rahasia123',
    ])->assertSessionHasErrors('nisn');

    $this->assertGuest();
});

it('menolak akun staff di portal siswa', function () {
    buatSiswa(['nisn' => '0987654321', 'is_staff' => true]);

    $this->post(route('siswa.login.store'), [
        'nisn' => '0987654321',
        'password' => 'rahasia123',
    ])->assertRedirect(route('siswa.login'));

    $this->assertGuest();
});

it('menolak setelah 5x percobaan gagal', function () {
    buatSiswa();

    for ($i = 0; $i < 5; $i++) {
        $this->post(route('siswa.login.store'), [
            'nisn' => '1234567890',
            'password' => 'salah-password',
        ])->assertRedirect(route('siswa.login'));
    }

    // Upaya ke-6 dalam 1 menit diblokir middleware throttle (429).
    $this->post(route('siswa.login.store'), [
        'nisn' => '1234567890',
        'password' => 'salah-password',
    ])->assertTooManyRequests();

    $this->assertGuest();
});

it('guest tidak bisa membuka dashboard siswa', function () {
    $this->get(route('siswa.dashboard'))->assertRedirect(route('login'));
});

it('siswa yang login bisa membuka dashboard', function () {
    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('Halo, Siswa!', false)
        ->assertSee('Siap meraih hasil terbaik hari ini?', false);
});

it('dashboard menampilkan ujian berikutnya dan kalender', function () {
    Exam::create([
        'name' => 'Ujian Matematika',
        'min_score' => 75,
        'duration' => 90,
        'conduct_at' => now()->addDay(),
        'exact_time' => true,
    ]);

    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('Ujian Matematika', false)
        ->assertSee('Terjadwal', false)
        ->assertSee('Ujian Berikutnya', false)
        ->assertSee('Riwayat Ujian Terbaru', false)
        ->assertDontSee('Aktivitas Mingguan', false)
        ->assertSee('Kalender Minggu Ini', false)
        ->assertSee('Ujian Mendatang', false)
        ->assertSee('1,5 jam', false)
        // Ikon mata pelajaran (aset lokal) menggantikan ikon generik.
        ->assertSee('images/subjects/matematika.svg', false);
});

it('dashboard tanpa ujian menampilkan status kosong', function () {
    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('Belum ada ujian terjadwal', false)
        ->assertSee('images/illustrations/empty-ujian.svg', false)
        ->assertSee('0 jam', false);
});

it('kalender bisa pindah bulan via query', function () {
    $cal = now()->addMonth()->format('Y-m');

    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard', ['cal' => $cal]))
        ->assertOk()
        ->assertSee('Kalender Minggu Ini', false);
});

it('foto siswa yang diunggah admin tampil sebagai avatar di dashboard', function () {
    Storage::fake('public');
    Storage::disk('public')->put('siswa/foto-uji.jpg', 'dummy');

    $user = buatSiswa();

    $siswa = $user->siswa()->create([
        'name' => 'Siswa Uji',
        'nisn' => '1234567890',
        'kelas' => 'XI IPB 1',
        'jenis_kelamin' => 'L',
        'foto_path' => 'siswa/foto-uji.jpg',
    ]);

    // Observer menyinkronkan siswas.foto_path -> users.photo_path.
    expect($user->fresh()->photo_path)->toBe('siswa/foto-uji.jpg');

    // Request nyata selalu memuat user dari database, jadi segarkan instance ini.
    $user->refresh();

    $this->actingAs($user)
        ->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('siswa/foto-uji.jpg', false)
        ->assertSee('XI IPB 1', false);

    // Menghapus foto mengembalikan avatar ke default.
    $siswa->update(['foto_path' => null]);

    expect($user->fresh()->photo_path)->toBeNull();
});

it('siswa tidak bisa membuka screen bawaan Orchid', function () {
    $this->actingAs(buatSiswa())
        ->get(config('app.url').'/siswa/users')
        ->assertForbidden();
});

it('siswa bisa logout', function () {
    $this->actingAs(buatSiswa());

    $this->post(route('siswa.logout'))->assertRedirect(route('siswa.login'));

    $this->assertGuest();
});

it('panel admin Filament tetap bisa diakses', function () {
    $this->get(config('app.url').'/admin/login')->assertOk();
});

it('aset tema biru siswa tersedia dan dimuat dashboard', function () {
    expect(file_exists(public_path('css/siswa-theme.css')))->toBeTrue();
    expect(file_exists(public_path('js/siswa-theme.js')))->toBeTrue();

    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('/css/siswa-theme.css', false)
        ->assertSee('/js/siswa-theme.js', false)
        ->assertSee('siswa-bs-theme', false);
});

it('aset gambar dashboard tersedia', function () {
    foreach ([
        'images/brand/logo-cerdas-cbt.svg',
        'images/illustrations/hero-belajar.svg',
        'images/illustrations/empty-ujian.svg',
        'images/avatar/student-default.svg',
        'images/subjects/matematika.svg',
        'images/subjects/bahasa-indonesia.svg',
        'images/subjects/fisika.svg',
        'images/subjects/informatika.svg',
        'images/subjects/sejarah-indonesia.svg',
        'images/subjects/default.svg',
    ] as $path) {
        expect(file_exists(public_path($path)))->toBeTrue("Aset hilang: {$path}");
    }

    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('images/brand/logo-cerdas-cbt.svg', false)
        ->assertSee('images/illustrations/hero-belajar.svg', false);
});

it('font Inter dimuat lokal, bukan dari CDN eksternal', function () {
    // Portal siswa harus aman dipakai di jaringan sekolah tanpa internet.
    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertDontSee('fonts.bunny.net', false)
        ->assertDontSee('fonts.googleapis.com', false)
        ->assertDontSee('fonts.gstatic.com', false);
});

it('halaman login memuat font secara lokal, bukan dari CDN eksternal', function () {
    $this->get(route('siswa.login'))
        ->assertOk()
        ->assertDontSee('fonts.bunny.net', false)
        ->assertDontSee('fonts.googleapis.com', false)
        ->assertDontSee('fonts.gstatic.com', false);
});

it('sidebar dashboard punya tombol toggle collapse dan backdrop drawer', function () {
    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard'))
        ->assertOk()
        ->assertSee('id="siswaSidebarToggle"', false)
        ->assertSee('id="siswaMenuToggle"', false)
        ->assertSee('id="siswaMenuBackdrop"', false)
        ->assertSee('aria-controls="headerMenuCollapse"', false)
        ->assertSee('aria-expanded="true"', false);
});

it('status sidebar disimpan di localStorage dan diterapkan sebelum render', function () {
    $this->actingAs(buatSiswa())
        ->get(route('siswa.dashboard'))
        ->assertOk()
        // Inline script anti-FOUC menyetel atribut di <html> dari localStorage.
        ->assertSee('siswa-sidebar', false)
        ->assertSee('data-siswa-sidebar', false);
});

it('CSS sidebar menyediakan rail desktop dan drawer mobile', function () {
    $css = file_get_contents(public_path('css/siswa-theme.css'));

    expect($css)->toContain("html[data-siswa-sidebar='collapsed']")
        ->toContain('.siswa-backdrop')
        ->toContain('body.menu-open .workspace')
        // Perbaikan padding nol dari `container-xl p-0` bawaan Orchid.
        ->toContain('.container-xl.p-0');
});

it('JS portal menangani toggle sidebar, tooltip, dan navigasi Turbo', function () {
    $js = file_get_contents(public_path('js/siswa-theme.js'));

    expect($js)->toContain('siswa-sidebar')
        ->toContain('data-label')
        ->toContain('turbo:load')
        ->toContain('siswaMenuBackdrop');
});
