<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Masuk | RMDI</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                background: #f4f4f4;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 min-h-dvh flex items-center justify-center p-4">
        <main class="w-full max-w-xl">
            <section class="relative overflow-hidden rounded-[32px] bg-white p-10 shadow-[0_24px_50px_rgba(0,0,0,0.08)] border border-gray-200">
                <div class="relative z-10">
                    <div class="flex flex-col items-center gap-6 text-center">
                        <img src="{{ asset('images/RMDI_logo.png') }}" alt="RMDI" class="h-16 object-contain" loading="lazy" />
                        <div class="flex h-16 w-16 items-center justify-center rounded-full border-4 border-white bg-[#123a5d] text-white shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-9 w-9">
                                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.42 0-8 2.24-8 5v1a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1c0-2.76-3.58-5-8-5Z" />
                            </svg>
                        </div>
                    </div>
                    <form class="mt-10 space-y-6">
                        <div class="text-left">
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                            <div class="mt-2">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="Masukkan email Anda"
                                    class="w-full rounded-2xl border border-gray-300 bg-white px-5 py-3 text-base text-gray-800 shadow-inner focus:border-[#123a5d] focus:outline-none focus:ring-2 focus:ring-[#5ba1d3]"
                                    autocomplete="email"
                                />
                            </div>
                        </div>

                        <div class="text-left">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                            <div class="mt-2 relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan kata sandi Anda"
                                    class="w-full rounded-2xl border border-gray-300 bg-white px-5 py-3 text-base text-gray-800 shadow-inner focus:border-[#123a5d] focus:outline-none focus:ring-2 focus:ring-[#5ba1d3]"
                                    autocomplete="current-password"
                                />
                                <button type="button" class="absolute inset-y-0 right-4 flex items-center text-gray-500 transition hover:text-[#123a5d]" data-toggle-password>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-[#1f66a6] py-3 text-lg font-semibold text-white transition hover:bg-[#1a578d] focus:outline-none focus:ring-4 focus:ring-[#5ba1d3]/60"
                        >
                            Masuk
                        </button>
                    </form>
                    <p class="mt-6 text-center text-sm text-gray-600">
                        Lupa Password Anda?
                        <a href="#" class="font-semibold text-[#1f66a6] hover:underline">Konfirmasi</a>
                    </p>
                </div>
            </section>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toggleButton = document.querySelector('[data-toggle-password]');
                const passwordInput = document.getElementById('password');

                if (!toggleButton || !passwordInput) {
                    return;
                }

                toggleButton.addEventListener('click', () => {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    toggleButton.setAttribute('aria-pressed', String(isPassword));
                    toggleButton.innerHTML = isPassword
                        ? `
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                <path d="M17.94 17.94A10.84 10.84 0 0 1 12 20c-7 0-11-8-11-8a21.52 21.52 0 0 1 5.06-5.94" />
                                <path d="M1 1l22 22" />
                                <path d="M9.88 9.88a3 3 0 0 0 4.24 4.24" />
                            </svg>
                        `
                        : `
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        `;
                });
            });
        </script>
    </body>
</html>
