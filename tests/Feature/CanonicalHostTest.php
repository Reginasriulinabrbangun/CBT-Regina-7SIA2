<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('me-redirect host non-kanonis ke host APP_URL', function () {
    $appUrl = config('app.url');
    $appHost = parse_url($appUrl, PHP_URL_HOST);

    // Hanya relevan bila APP_URL menunjuk host non-localhost (mis. 127.0.0.1).
    if ($appHost === 'localhost' || $appHost === null || $appHost === '') {
        $this->markTestSkipped('APP_URL sudah localhost.');
    }

    $this->get('http://localhost/siswa/login')
        ->assertRedirect($appUrl.'/siswa/login');
});

it('tidak me-redirect bila sudah di host kanonis', function () {
    $this->get(route('siswa.login'))->assertOk();
});

it('health check tidak ikut di-redirect', function () {
    $this->get('http://localhost/up')->assertOk();
    $this->get(config('app.url').'/up')->assertOk();
});
