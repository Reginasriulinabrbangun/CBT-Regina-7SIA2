{{-- Topbar portal siswa: pencarian, notifikasi, dan menu profil. Hanya di halaman dashboard. --}}
<div class="siswa-topbar d-flex align-items-center gap-3 mb-3">
    <div class="siswa-search flex-grow-1">
        <x-orchid-icon path="bs.search" class="siswa-search__icon" aria-hidden="true"/>
        <input type="search"
               class="form-control siswa-search__input"
               placeholder="Cari mata pelajaran, ujian…"
               aria-label="Cari mata pelajaran atau ujian"
               autocomplete="off">
    </div>

    <div class="siswa-topbar__actions d-flex align-items-center gap-2 ms-auto">
        <x-orchid-notification/>

        <div class="dropdown">
            <button class="btn siswa-profile-toggle d-flex align-items-center gap-2" type="button"
                    id="siswaProfileDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false" aria-haspopup="true">
                <img src="{{ Auth::user()->user_avatar_url }}"
                     alt="Avatar {{ Auth::user()->name }}"
                     class="rounded-circle flex-shrink-0"
                     width="32" height="32" loading="lazy">
                <span class="siswa-profile-toggle__name text-truncate d-none d-sm-inline">{{ Auth::user()->name }}</span>
                <x-orchid-icon path="bs.chevron-down" width="0.75em" height="0.75em" aria-hidden="true"/>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="siswaProfileDropdown">
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route(config('platform.profile', 'platform.profile')) }}">
                        <x-orchid-icon path="bs.person" width="0.9em" height="0.9em"/>
                        Profil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('siswa.logout') }}">
                        <x-orchid-icon path="bs.box-arrow-right" width="0.9em" height="0.9em"/>
                        Keluar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
