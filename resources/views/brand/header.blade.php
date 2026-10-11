@push('head')
    {{-- Inter self-host (dari build Vite) untuk seluruh portal siswa. --}}
    @fonts

    <script>
        // Terapkan tema tersimpan sebelum render (anti-flicker). Default: light.
        try {
            document.documentElement.setAttribute(
                'data-bs-theme',
                localStorage.getItem('siswa-bs-theme') === 'dark' ? 'dark' : 'light'
            );
        } catch (e) {
            document.documentElement.setAttribute('data-bs-theme', 'light');
        }
    </script>
@endpush

{{-- Brand CERDAS CBT (tampil di sidebar portal siswa). --}}
<div class="siswa-brand d-flex align-items-center">
    <span class="siswa-brand__mark">
        <img src="{{ asset('images/brand/logo-cerdas-cbt.svg') }}" alt="Logo CERDAS CBT" width="40" height="40">
    </span>

    <span>
        <span class="siswa-brand__name">CERDAS CBT</span>
        <span class="siswa-brand__tagline d-none d-lg-block">Sistem Ujian Digital untuk Pendidikan Lebih Baik</span>
    </span>
</div>
