<div>
    <h1 class="text-3xl font-bold tracking-tight text-on-surface-strong dark:text-on-surface-dark-strong">Masuk ke Akun
        Siswa</h1>
    <p class="mt-2 text-sm text-on-surface dark:text-on-surface-dark">Silakan masuk untuk melanjutkan.</p>

    <form method="POST" action="{{ route('siswa.login.store') }}" class="mt-8 grid gap-y-5"
        x-data="{ submitting: false, show: false }" @submit="submitting = true">
        @csrf

        @if ($errors->any())
            <div class="rounded-radius border border-danger bg-danger/10 p-4 text-sm text-on-surface dark:border-danger dark:bg-danger/10 dark:text-on-surface-dark"
                role="alert">
                <div class="flex gap-x-2">
                    <svg class="flex-none size-4 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a1 1 0 0 0 0 2v3a1 1 0 0 0 1 1h1a1 1 0 1 0 0-2v-3a1 1 0 0 0-1-1H9Z"
                            clip-rule="evenodd" />
                    </svg>
                    <p>{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <div>
            <label for="nisn"
                class="block text-sm font-medium mb-2 text-on-surface-strong dark:text-on-surface-dark-strong">NISN
                <span class="text-danger">*</span></label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-on-surface/40 dark:text-on-surface-dark/40"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                </svg>
                <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan NISN"
                    maxlength="10" inputmode="numeric" required autofocus @class([
                        'w-full rounded-radius border bg-surface ps-10 pe-3 py-2.5 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark',
                        'border-outline focus-visible:outline-primary dark:border-outline-dark dark:focus-visible:outline-primary-dark' => !$errors->has('nisn'),
                        'border-danger focus-visible:outline-danger dark:border-danger dark:focus-visible:outline-danger' => $errors->has('nisn'),
                    ])>
            </div>
            @error('nisn')
                <p class="text-xs text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password"
                class="block text-sm font-medium mb-2 text-on-surface-strong dark:text-on-surface-dark-strong">Password
                <span class="text-danger">*</span></label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-on-surface/40 dark:text-on-surface-dark/40"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z"
                        clip-rule="evenodd" />
                </svg>
                <input id="password" :type="show ? 'text' : 'password'" name="password"
                    placeholder="Masukkan kata sandi" required autocomplete="current-password" @class([
                        'w-full rounded-radius border bg-surface ps-10 pe-10 py-2.5 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark',
                        'border-outline focus-visible:outline-primary dark:border-outline-dark dark:focus-visible:outline-primary-dark' => !$errors->has('password'),
                        'border-danger focus-visible:outline-danger dark:border-danger dark:focus-visible:outline-danger' => $errors->has('password'),
                    ])>
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 end-0 flex items-center px-2.5 text-on-surface/50 hover:text-on-surface-strong focus-visible:outline-2 focus-visible:outline-primary dark:text-on-surface-dark/50 dark:hover:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark"
                    aria-label="Tampilkan/sembunyikan kata sandi">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="size-5 shrink-0" aria-hidden="true">
                        <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
                        <path fill-rule="evenodd"
                            d="M.664 10.59a1.651 1.651 0 0 1 0-1.18 23.848 23.848 0 0 1 7.567-5.181 23.834 23.834 0 0 1 3.769-1.46 23.805 23.805 0 0 1 3.769 1.46 23.848 23.848 0 0 1 7.567 5.18 1.651 1.651 0 0 1 0 1.18 23.848 23.848 0 0 1-7.567 5.181 23.834 23.834 0 0 1-3.769 1.46 23.805 23.805 0 0 1-3.769-1.46A23.848 23.848 0 0 1 .664 10.59ZM13.918 9.75a3.168 3.168 0 0 0-3.168-3.168 3.168 3.168 0 0 0-2.55 1.272.75.75 0 0 1-1.15-.96 4.668 4.668 0 0 1 3.7-1.812 4.668 4.668 0 0 1 4.668 4.668 4.667 4.667 0 0 1-1.812 3.7.75.75 0 0 1-.96-1.15 3.168 3.168 0 0 0 1.272-2.55Z"
                            clip-rule="evenodd" />
                    </svg>
                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" class="size-5 shrink-0" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M3.28 2.22a.75.75 0 0 0-1.06 1.06l14.5 14.5a.75.75 0 1 0 1.06-1.06L3.28 2.22ZM8.159 8.753A3.75 3.75 0 0 1 11.25 7.5c.758 0 1.47.187 2.096.518l-1.844 1.844a1.875 1.875 0 0 0-2.686 2.686L8.16 8.753ZM17.163 18.55A23.752 23.752 0 0 1 10 15.57c-2.036 0-3.926.553-5.53 1.513A.75.75 0 0 0 3.722 18.5a25.253 25.253 0 0 0 6.278-1.75 25.25 25.25 0 0 0 6.278 1.75.75.75 0 0 0 .885-1.45Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>

        <label for="remember"
            class="flex w-fit cursor-pointer items-center gap-2 text-sm text-on-surface dark:text-on-surface-dark">
            <input type="hidden" name="remember" value="0">
            <input type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))
                class="size-4 shrink-0 rounded-radius border-outline bg-surface accent-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark dark:accent-primary-dark dark:focus-visible:outline-primary-dark">
            Ingat saya
        </label>

        <button type="submit" :disabled="submitting"
            class="w-full whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-2.5 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark inline-flex items-center justify-center gap-2">
            <svg x-show="submitting" x-cloak class="animate-spin size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z" />
            </svg>
            <span x-text="submitting ? 'Memproses…' : 'Masuk'"></span>
            <svg x-show="!submitting" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-4 shrink-0" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </form>
</div>