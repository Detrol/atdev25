<!DOCTYPE html>
<html lang="sv" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Darkmode FOIT Fix - Must run before any CSS loads -->
    <script>
        (function() {
            const mode = localStorage.getItem('darkMode') || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = (mode === 'dark') || (mode === 'system' && prefersDark);

            if (isDark) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <title>Admin Login - ATDev</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-950 dark:to-gray-900">
    <!-- Darkmode Toggle -->
    <div class="fixed top-4 right-4 z-50">
        <x-darkmode-toggle size="sm" />
    </div>

    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                AT<span class="text-indigo-600 dark:text-indigo-400">Dev</span>
            </h2>
            <h3 class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">
                Admin Login
            </h3>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="bg-white/80 dark:bg-gray-800/90 backdrop-blur-xl px-6 py-12 shadow-xl dark:shadow-2xl dark:shadow-black/50 sm:rounded-2xl sm:px-12 border border-gray-200/50 dark:border-gray-700/50">
                @if($errors->any())
                    <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/20 p-4 border border-red-100 dark:border-red-800/30">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Felaktiga inloggningsuppgifter</h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                                    <p>Kontrollera din e-post och lösenord och försök igen.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="/admin/login" method="POST">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">E-postadress</label>
                        <div class="mt-2">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                value="{{ old('email') }}"
                                class="block w-full rounded-lg border-0 py-2.5 text-gray-900 dark:text-white bg-white dark:bg-gray-900/50 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-400 sm:text-sm sm:leading-6 px-4 transition-all duration-200"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Lösenord</label>
                        <div class="mt-2">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="block w-full rounded-lg border-0 py-2.5 text-gray-900 dark:text-white bg-white dark:bg-gray-900/50 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-400 sm:text-sm sm:leading-6 px-4 transition-all duration-200"
                            >
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-900/50 text-indigo-600 dark:text-indigo-500 focus:ring-indigo-600 dark:focus:ring-indigo-400 transition-all duration-200"
                            >
                            <label for="remember" class="ml-3 block text-sm leading-6 text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                                Kom ihåg mig
                            </label>
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-lg bg-indigo-600 dark:bg-indigo-500 px-3 py-2.5 text-sm font-semibold text-white shadow-lg hover:shadow-xl hover:bg-indigo-500 dark:hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-indigo-400 transition-all duration-200 active:scale-95"
                        >
                            Logga in
                        </button>
                    </div>
                </form>
            </div>

            <p class="mt-10 text-center text-sm text-gray-600 dark:text-gray-400">
                <a href="/" class="font-semibold leading-6 text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors duration-200">
                    ← Tillbaka till startsidan
                </a>
            </p>
        </div>
    </div>
</body>
</html>
