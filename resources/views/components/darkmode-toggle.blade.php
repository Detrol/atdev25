{{--
Darkmode Toggle Component

Tre-läges toggle för darkmode: Ljust, Mörkt, System
Använder Alpine.store('darkMode') för global state

Props:
@param string $size - Storlek: 'sm', 'md', 'lg' (default: 'md')
@param bool $showLabel - Visa text-label (default: false)
@param string $class - Extra CSS-klasser (default: '')
--}}

<div
    x-data
    {{ $attributes->merge(['class' => $class]) }}
>
    <button
        type="button"
        @click="$store.darkMode.toggle()"
        class="relative inline-flex items-center gap-2 rounded-lg
               bg-white dark:bg-gray-800
               border border-gray-300 dark:border-gray-600
               hover:bg-gray-50 dark:hover:bg-gray-700
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900
               transition-all duration-200
               {{ $getSizeClasses() }}"
        :aria-label="
            $store.darkMode?.mode === 'light' ? 'Byt till mörkt läge' :
            $store.darkMode?.mode === 'dark' ? 'Byt till systemläge' :
            'Byt till ljust läge'
        "
        :title="
            $store.darkMode?.mode === 'light' ? 'Ljust läge (klicka för mörkt)' :
            $store.darkMode?.mode === 'dark' ? 'Mörkt läge (klicka för systemläge)' :
            'Systemläge (klicka för ljust)'
        "
    >
        <!-- Sol-ikon (Light Mode) -->
        <svg
            x-show="$store.darkMode?.mode === 'light'"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="{{ $getIconSize() }} text-yellow-500"
            aria-hidden="true"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
        </svg>

        <!-- Måne-ikon (Dark Mode) -->
        <svg
            x-show="$store.darkMode?.mode === 'dark'"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="{{ $getIconSize() }} text-blue-400"
            aria-hidden="true"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
        </svg>

        <!-- Dator-ikon (System Mode) -->
        <svg
            x-show="$store.darkMode?.mode === 'system'"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="{{ $getIconSize() }} text-gray-600 dark:text-gray-300"
            aria-hidden="true"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
        </svg>

        <!-- Fallback-ikon (visas medan Alpine laddar) -->
        <svg
            x-show="!$store.darkMode"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="{{ $getIconSize() }} text-gray-400 dark:text-gray-500 animate-pulse"
            aria-hidden="true"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
        </svg>

        <!-- Optional text label -->
        @if($showLabel)
            <span
                class="text-sm font-medium text-gray-700 dark:text-gray-200"
                x-text="
                    $store.darkMode?.mode === 'light' ? 'Ljust' :
                    $store.darkMode?.mode === 'dark' ? 'Mörkt' :
                    'System'
                "
            ></span>
        @endif
    </button>
</div>
