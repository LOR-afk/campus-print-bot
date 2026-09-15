<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Campus Print Bot') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-50 text-slate-900">

<div class="min-h-screen lg:flex">

    <aside class="hidden w-64 flex-col bg-slate-950 text-white lg:flex">

        <div class="border-b border-slate-800 px-6 py-6">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500 text-xl font-bold">
                    CP
                </div>

                <div>
                    <h1 class="font-bold">
                        Campus Print
                    </h1>

                    <p class="text-xs text-slate-400">
                        Admin Panel
                    </p>
                </div>
            </div>
        </div>

        <nav class="flex-1 space-y-2 px-4 py-6">

            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition
                {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
                <span>▦</span>
                <span>Dashboard</span>
            </a>

            <a
                href="{{ route('printers.index') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition
                {{ request()->routeIs('printers.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
            >
                <span>▣</span>
                <span>Printers</span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-400"
            >
                <span>≡</span>
                <span>Queue</span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-400"
            >
                <span>◉</span>
                <span>Notifications</span>
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-400"
            >
                <span>◌</span>
                <span>Messenger</span>
            </a>

        </nav>

        <div class="border-t border-slate-800 p-4">

            <div class="mb-4 rounded-xl bg-slate-900 p-4">
                <p class="text-sm font-semibold">
                    {{ auth()->user()->name }}
                </p>

                <p class="mt-1 truncate text-xs text-slate-400">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-red-300 transition hover:bg-red-500/10"
                >
                    Logout
                </button>
            </form>

        </div>

    </aside>

    <div class="flex min-w-0 flex-1 flex-col">

        <header class="border-b border-slate-200 bg-white px-5 py-4 lg:px-8">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Campus Print Bot
                    </p>

                    <p class="font-semibold text-slate-900">
                        Administration
                    </p>
                </div>

                <div class="flex items-center gap-3">

                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 lg:hidden"
                    >
                        Dashboard
                    </a>

                    <a
                        href="{{ route('printers.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 lg:hidden"
                    >
                        Printers
                    </a>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                </div>

            </div>

        </header>

        <main class="flex-1">
            @yield('content')
        </main>

    </div>

</div>

@livewireScripts

</body>
</html>