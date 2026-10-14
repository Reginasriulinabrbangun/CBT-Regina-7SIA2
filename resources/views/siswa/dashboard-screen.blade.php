{{-- Hero: sapaan + banner ajakan --}}
<div class="row g-3 mb-3">
    <div class="col-xl-8">
        <div class="siswa-hero h-100 d-flex flex-column justify-content-center">
            <h1 class="siswa-hero__title mb-1 text-white">Halo, {{ $namaDepan }}! </h1>
            <p class="siswa-hero__subtitle mb-0">Siap meraih hasil terbaik hari ini?</p>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="siswa-hero__banner h-100 d-flex align-items-center gap-3">
            <img src="{{ asset('images/illustrations/hero-belajar.svg') }}" alt="" class="siswa-hero__art flex-shrink-0"
                loading="lazy">
            <p class="mb-0 small fw-medium">Terus belajar, raih masa depan yang lebih baik!</p>
        </div>
    </div>
</div>

{{-- Statistik utama --}}
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card stat-card--blue h-100">
            <div class="card-body d-flex align-items-start gap-3">
                <span class="stat-card__icon">
                    <x-orchid-icon path="bs.calendar-event" width="1.25em" height="1.25em" />
                </span>
                <div class="flex-grow-1">
                    <p class="stat-card__label mb-1">Ujian Mendatang</p>
                    <p class="stat-card__value mb-0">{{ str_pad((string) $metrics['mendatang'], 2, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <span class="stat-card__badge">Terjadwal</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card stat-card--teal h-100">
            <div class="card-body d-flex align-items-start gap-3">
                <span class="stat-card__icon">
                    <x-orchid-icon path="bs.check-circle" width="1.25em" height="1.25em" />
                </span>
                <div class="flex-grow-1">
                    <p class="stat-card__label mb-1">Ujian Selesai</p>
                    <p class="stat-card__value mb-0">{{ str_pad((string) $metrics['selesai'], 2, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <span class="stat-card__badge">{{ $metrics['selesai'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card stat-card--purple h-100">
            <div class="card-body d-flex align-items-start gap-3">
                <span class="stat-card__icon">
                    <x-orchid-icon path="bs.graph-up-arrow" width="1.25em" height="1.25em" />
                </span>
                <div class="flex-grow-1">
                    <p class="stat-card__label mb-1">Rata-rata Nilai</p>
                    <p class="stat-card__value mb-0">{{ $rataNilai }}</p>
                </div>
                <span class="stat-card__badge">Nilai</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card stat-card--orange h-100">
            <div class="card-body d-flex align-items-start gap-3">
                <span class="stat-card__icon">
                    <x-orchid-icon path="bs.clock" width="1.25em" height="1.25em" />
                </span>
                <div class="flex-grow-1">
                    <p class="stat-card__label mb-1">Total Waktu Ujian</p>
                    <p class="stat-card__value mb-0">{{ $metrics['waktu'] }}</p>
                </div>
                <span class="stat-card__badge">Waktu</span>
            </div>
        </div>
    </div>
</div>

{{-- Kolom kiri: ujian berikutnya + riwayat; kolom kanan: kalender --}}
<div class="row g-3">
    <div class="col-xl-8 d-flex flex-column gap-3">
        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <strong>Ujian Berikutnya</strong>
                @if ($nextExam)
                    <span class="badge text-bg-info">● Terjadwal</span>
                @endif
            </div>
            <div class="card-body">
                @if ($nextExam)
                    <div class="d-flex gap-3 align-items-start">
                        <span class="stat-card__icon stat-card__icon--lg stat-card__icon--blue flex-shrink-0">
                            <img src="{{ $nextExamIcon }}" alt="" width="26" height="26" loading="lazy">
                        </span>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">{{ $nextExam->name }}</h5>
                            <p class="text-muted small mb-1">Kelas {{ $siswa?->kelas ?: '-' }}</p>
                            <p class="text-muted small mb-0">
                                {{ ucfirst($nextExam->conduct_at?->locale('id')->translatedFormat('l, d F Y') ?? '-') }}
                                &nbsp;•&nbsp;
                                {{ $nextExam->conduct_at?->format('H.i') }}–{{ $nextExam->expired_at?->format('H.i') ?? '' }}
                                WIB
                                &nbsp;•&nbsp; Durasi {{ $nextExam->duration }} menit
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top d-flex flex-wrap gap-2">
                        <a href="#kalender-ujian" class="btn btn-primary">Lihat Detail</a>
                        <a href="#kalender-ujian" class="btn btn-outline-primary">Lihat Semua Jadwal</a>
                    </div>
                @else
                    <div class="siswa-empty text-center py-3">
                        <img src="{{ asset('images/illustrations/empty-ujian.svg') }}" alt="" class="siswa-empty__art"
                            loading="lazy">
                        <p class="fw-semibold mb-1">Belum ada ujian terjadwal</p>
                        <p class="text-muted small mb-0">Silakan hubungi guru/admin jika Anda merasa ini keliru.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <strong>Riwayat Ujian Terbaru</strong>
                <a href="#" class="small">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="text-muted small fw-medium">Mata Pelajaran</th>
                                <th scope="col" class="text-muted small fw-medium">Tanggal Ujian</th>
                                <th scope="col" class="text-muted small fw-medium">Nilai</th>
                                <th scope="col" class="text-muted small fw-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat as $row)
                                <tr>
                                    <td class="fw-medium">
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <img src="{{ $row['ikon'] }}" alt="" width="20" height="20" loading="lazy">
                                            {{ $row['mapel'] }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $row['tanggal'] }}</td>
                                    <td>
                                        @if ($row['nilai'] !== null)
                                            <span class="badge text-bg-light border">{{ $row['nilai'] }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td><span class="badge text-bg-success">✓ Selesai</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($riwayatIsSample)
                    <p class="text-muted small px-3 py-2 mb-0">Data contoh untuk tampilan.</p>
                @else
                    <p class="text-muted small px-3 py-2 mb-0">Nilai akan tampil setelah fitur penilaian tersedia.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-4" id="kalender-ujian">
        <div class="card h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <strong>Kalender Minggu Ini</strong>
                <div class="d-flex align-items-center gap-1">
                    <a href="{{ request()->fullUrlWithQuery(['cal' => $calendar['prev']]) }}"
                        class="btn btn-sm btn-link text-decoration-none" aria-label="Bulan sebelumnya">‹</a>
                    <span class="small fw-bold text-nowrap">{{ $calendar['label'] }}</span>
                    <a href="{{ request()->fullUrlWithQuery(['cal' => $calendar['next']]) }}"
                        class="btn btn-sm btn-link text-decoration-none" aria-label="Bulan berikutnya">›</a>
                </div>
            </div>
            <div class="card-body">
                <div class="siswa-calendar">
                    <div class="siswa-calendar__head">
                        @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $namaHari)
                            <span class="siswa-calendar__dow">{{ $namaHari }}</span>
                        @endforeach
                    </div>
                    <div class="siswa-calendar__grid">
                        @for ($i = 0; $i < $calendar['offset']; $i++)
                            <span class="siswa-calendar__cell is-empty"></span>
                        @endfor
                        @foreach ($calendar['days'] as $hari)
                            <span class="siswa-calendar__cell {{ $calendar['selected'] === $hari ? 'is-selected' : '' }}">
                                <span class="siswa-calendar__day">{{ $hari }}</span>
                                @if (isset($calendar['examDays'][$hari]) || isset($calendar['doneDays'][$hari]))
                                    <span
                                        class="siswa-calendar__dot {{ $calendar['selected'] === $hari ? 'is-on-selected' : (isset($calendar['examDays'][$hari]) ? 'is-exam' : 'is-done') }}"></span>
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3 border-top mt-2 pt-2 small text-muted">
                    <span><span class="siswa-legend__dot bg-primary me-1"></span> Hari Ujian</span>
                    <span><span class="siswa-legend__dot bg-success me-1"></span> Sudah Dikerjakan</span>
                </div>
            </div>
        </div>
    </div>
</div>