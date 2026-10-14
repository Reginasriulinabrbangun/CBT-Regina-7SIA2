<x-layouts.siswa-guest title="Masuk Siswa">
    <div class="min-h-screen grid lg:grid-cols-2 lg:h-screen lg:overflow-hidden">
        <!-- Panel visual -->
        <div
            class="relative hidden lg:flex flex-col overflow-hidden bg-gradient-to-br from-blue-100 via-blue-50 to-white px-10 xl:px-14 text-slate-800">
            <div class="pointer-events-none absolute -top-32 -right-32 size-[380px] xl:size-[440px] rounded-full bg-blue-200/60"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-28 -left-28 size-72 rounded-full bg-blue-300/50"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute top-1/3 left-1/4 size-40 rounded-full bg-sky-100/80"
                aria-hidden="true"></div>

            <div class="relative z-10 my-auto flex flex-col gap-5 py-10">
                <div class="flex items-center gap-x-2.5">
                    <span
                        class="grid size-11 flex-none place-items-center rounded-radius bg-primary text-on-primary dark:bg-primary-dark dark:text-on-primary-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6"
                            aria-hidden="true">
                            <path
                                d="M10.394 2.08a1 1 0 0 0-.788 0l-7 3a1 1 0 0 0 0 1.84L5.25 8.051a.999.999 0 0 1 .356-.257l4-1.714a1 1 0 1 1 .788 1.838L7.667 9.088l1.94.831a1 1 0 0 0 .787 0l7-3a1 1 0 0 0 0-1.838l-7-3Z" />
                            <path
                                d="M3.31 9.223a1 1 0 0 0-1.022 1.222l.846 4.095a1 1 0 0 0 .984.757h9.39a1 1 0 0 0 .984-.757l.846-4.095a1 1 0 0 0-1.022-1.222L10 11.557 3.31 9.223Z" />
                        </svg>
                    </span>
                    <span>
                        <span
                            class="block text-xl font-extrabold tracking-tight text-blue-700 dark:text-blue-400">CBT</span>
                        <span class="block text-[11px] font-medium text-slate-500 dark:text-neutral-400">Ujian Online,
                            Masa Depan Lebih Baik</span>
                    </span>
                </div>

                <h1
                    class="text-4xl xl:text-5xl font-extrabold leading-[1.15] tracking-tight text-slate-900 dark:text-white">
                    Selamat Datang<br>
                </h1>
                <p class="max-w-md text-base leading-relaxed text-slate-500 dark:text-neutral-400">Masuk dan persiapkan
                    dirimu untuk meraih hasil terbaik.</p>
                <img src="{{ asset('images/illustrations/ilustrasi-siswa-laptop-4k-tanpa-latar.svg') }}"
                    alt="Ilustrasi siswa mengerjakan ujian online" loading="lazy"
                    class="w-auto max-w-md max-h-[36vh] xl:max-h-[38vh] object-contain drop-shadow-xl">
                <p class="text-xs text-slate-400 dark:text-neutral-500">Belajar • Berlatih • Meraih Prestasi</p>
            </div>
        </div>

        <!-- Panel form -->
        <div class="grid place-items-center overflow-y-auto bg-surface p-6 py-10 sm:p-10 dark:bg-surface-dark">
            <div class="w-full max-w-md">
                <div class="mb-8 flex items-center gap-x-2 lg:hidden">
                    <span
                        class="flex-none size-9 rounded-radius bg-primary text-on-primary grid place-items-center font-bold text-lg dark:bg-primary-dark dark:text-on-primary-dark">C</span>
                    <span class="text-lg font-bold text-on-surface-strong dark:text-on-surface-dark-strong">CBT</span>
                </div>
                @include('siswa.login-form')
                <p class="mt-10 text-center text-xs text-slate-400 dark:text-neutral-500">© 2026 CBT. Sistem Ujian
                    Digital.</p>
            </div>
        </div>
    </div>
</x-layouts.siswa-guest>