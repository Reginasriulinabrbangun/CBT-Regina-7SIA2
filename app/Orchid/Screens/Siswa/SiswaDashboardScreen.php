<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Siswa;

use App\Models\Exam;
use App\Support\SubjectIcon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class SiswaDashboardScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user = Auth::user()->loadMissing('siswa');
        $now = now();

        $exams = Exam::query()
            ->with('subjects:id,name')
            ->orderBy('conduct_at')
            ->get(['id', 'name', 'conduct_at', 'expired_at', 'duration']);

        $nextExam = $exams->firstWhere('conduct_at', '>', $now);

        $calParam = request()->query('cal');
        try {
            $cal = $calParam
                ? Carbon::createFromFormat('Y-m', $calParam)->startOfMonth()
                : ($nextExam?->conduct_at?->copy()->startOfMonth() ?? $now->copy()->startOfMonth());
        } catch (\Exception) {
            $cal = $now->copy()->startOfMonth();
        }

        $riwayat = $this->riwayat($exams, $now);
        $riwayatIsSample = $riwayat === [];

        if ($riwayatIsSample) {
            $riwayat = $this->contohRiwayat();
        }

        return [
            'user' => $user,
            'siswa' => $user->siswa,
            'namaDepan' => strtok($user->name ?? 'Siswa', ' '),
            'metrics' => [
                'mendatang' => $exams->where('conduct_at', '>', $now)->count(),
                'selesai' => $exams->filter(
                    fn ($exam) => $exam->expired_at ? $exam->expired_at->isPast() : $exam->conduct_at?->isPast()
                )->count(),
                'aktif' => $exams->filter(
                    fn ($exam) => $exam->conduct_at && $exam->conduct_at->lte($now)
                        && (! $exam->expired_at || $exam->expired_at->gte($now))
                )->count(),
                'waktu' => $this->formatJam((int) $exams->sum('duration')),
            ],
            'nextExam' => $nextExam,
            'nextExamIcon' => SubjectIcon::path($nextExam?->subjects->first()?->name ?? $nextExam?->name),
            'calendar' => $this->calendar($exams, $cal),
            'riwayat' => $riwayat,
            'riwayatIsSample' => $riwayatIsSample,
            'rataNilai' => $this->rataNilai($riwayat),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        $nama = strtok(Auth::user()->name ?? 'Siswa', ' ');

        return "Halo, {$nama}!";
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Siap meraih hasil terbaik hari ini?';
    }

    /**
     * The permissions required to access this screen.
     */
    public function permission(): ?iterable
    {
        return ['siswa.dashboard'];
    }

    /**
     * The screen's layout elements.
     */
    public function layout(): iterable
    {
        return [
            Layout::view('siswa.dashboard-screen'),
        ];
    }

    /**
     * Riwayat ujian lampau. Belum ada tabel nilai, jadi "nilai" bernilai null
     * dan hanya terisi pada data contoh.
     *
     * @param  \Illuminate\Support\Collection<int, Exam>  $exams
     * @return array<int, array{mapel: string, ikon: string, tanggal: string, nilai: int|null}>
     */
    protected function riwayat($exams, Carbon $now): array
    {
        return $exams
            ->filter(fn ($exam) => $exam->conduct_at
                && ($exam->expired_at ? $exam->expired_at->isPast() : $exam->conduct_at->isPast()))
            ->sortByDesc('conduct_at')
            ->take(4)
            ->map(function ($exam) {
                $mapel = $exam->subjects->first()?->name ?? $exam->name;

                return [
                    'mapel' => $mapel,
                    'ikon' => SubjectIcon::path($mapel),
                    'tanggal' => $exam->conduct_at?->locale('id')->translatedFormat('d M Y') ?? '-',
                    'nilai' => null,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Baris contoh untuk tampilan saat belum ada ujian yang selesai.
     *
     * @return array<int, array{mapel: string, ikon: string, tanggal: string, nilai: int|null}>
     */
    protected function contohRiwayat(): array
    {
        $rows = [
            ['mapel' => 'Bahasa Indonesia', 'tanggal' => '8 Okt 2026', 'nilai' => 92],
            ['mapel' => 'Fisika', 'tanggal' => '6 Okt 2026', 'nilai' => 85],
            ['mapel' => 'Informatika', 'tanggal' => '4 Okt 2026', 'nilai' => 90],
        ];

        return array_map(fn (array $row) => [
            'ikon' => SubjectIcon::path($row['mapel']),
            ...$row,
        ], $rows);
    }

    /**
     * Rata-rata nilai, dengan fallback contoh bila belum ada nilai asli.
     *
     * @param  array<int, array{mapel: string, ikon: string, tanggal: string, nilai: int|null}>  $riwayat
     */
    protected function rataNilai(array $riwayat): string
    {
        $nilai = collect($riwayat)->pluck('nilai')->filter(fn ($v) => $v !== null);

        return $nilai->isNotEmpty()
            ? number_format((float) $nilai->avg(), 1, ',', '')
            : '87,5';
    }

    protected function calendar($exams, Carbon $first): array
    {
        // Senin = 0 … Minggu = 6
        $offset = ($first->dayOfWeekIso - 1 + 7) % 7;
        $daysInMonth = (int) $first->format('t');

        $examDays = [];
        $doneDays = [];
        foreach ($exams as $exam) {
            if (! $exam->conduct_at) {
                continue;
            }
            if ((int) $exam->conduct_at->format('Y') !== (int) $first->format('Y')
                || (int) $exam->conduct_at->format('n') !== (int) $first->format('n')) {
                continue;
            }
            $day = (int) $exam->conduct_at->format('j');
            $selesai = $exam->expired_at ? $exam->expired_at->isPast() : $exam->conduct_at->isPast();
            if ($selesai) {
                $doneDays[$day] = true;
            } else {
                $examDays[$day] = true;
            }
        }

        $selected = null;
        $next = $exams->firstWhere('conduct_at', '>', now());
        if ($next?->conduct_at
            && (int) $next->conduct_at->format('Y') === (int) $first->format('Y')
            && (int) $next->conduct_at->format('n') === (int) $first->format('n')) {
            $selected = (int) $next->conduct_at->format('j');
        }

        return [
            'label' => ucfirst($first->locale('id')->translatedFormat('F Y')),
            'offset' => $offset,
            'days' => range(1, $daysInMonth),
            'examDays' => $examDays,
            'doneDays' => $doneDays,
            'selected' => $selected,
            'prev' => $first->copy()->subMonth()->format('Y-m'),
            'next' => $first->copy()->addMonth()->format('Y-m'),
        ];
    }

    protected function formatJam(int $menit): string
    {
        if ($menit <= 0) {
            return '0 jam';
        }

        $formatted = number_format($menit / 60, 1, ',', '');

        if (str_ends_with($formatted, ',0')) {
            $formatted = substr($formatted, 0, -2);
        }

        return $formatted.' jam';
    }
}
