{{-- Kartu user di sidebar (di bawah brand), sesuai mockup CERDAS CBT. --}}
@php($siswa = Auth::user()->siswa)
<div class="user-card mx-3 mt-2 mb-3 d-none d-md-flex align-items-center gap-3">
    <img src="{{ Auth::user()->user_avatar_url }}"
         alt="Avatar {{ Auth::user()->name }}"
         class="user-card__avatar rounded-circle shrink-0"
         width="40" height="40" loading="lazy">

    <span class="d-flex flex-column lh-sm overflow-hidden">
        <span class="user-card__name text-truncate">{{ Auth::user()->name }}</span>
        <span class="user-card__meta text-truncate">{{ $siswa?->kelas ?: '-' }}</span>
    </span>
</div>