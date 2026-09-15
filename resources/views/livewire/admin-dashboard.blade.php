<div class="p-5 sm:p-6 lg:p-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">
            Dashboard
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Monitor printer availability, queues, waiting time, and current operational status.
        </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Total Printers
            </p>

            <p class="mt-3 text-3xl font-bold text-slate-900">
                {{ $totalPrinters }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Available
            </p>

            <div class="mt-3 flex items-center justify-between">
                <p class="text-3xl font-bold text-green-600">
                    {{ $availablePrinters }}
                </p>

                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                    Online
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Unavailable
            </p>

            <div class="mt-3 flex items-center justify-between">
                <p class="text-3xl font-bold text-red-600">
                    {{ $unavailablePrinters }}
                </p>

                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                    Attention
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Total Queue
            </p>

            <p class="mt-3 text-3xl font-bold text-blue-600">
                {{ $totalQueue }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Average Wait
            </p>

            <p class="mt-3 text-3xl font-bold text-violet-600">
                {{ $averageWait }}
                <span class="text-sm font-semibold text-slate-400">
                    mins
                </span>
            </p>
        </div>

    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-3">

        <div class="xl:col-span-2">

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Printer Overview
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Current printer and queue information.
                        </p>
                    </div>

                    <a
                        href="{{ route('printers.index') }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                    >
                        Manage Printers
                    </a>

                </div>

                <div class="divide-y divide-slate-100">

                    @foreach ($printers as $printer)

                        <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex items-center gap-4">

                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-xl
                                    {{ $printer->status === 'available' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}"
                                >
                                    🖨
                                </div>

                                <div>
                                    <p class="font-semibold text-slate-900">
                                        {{ $printer->name }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $printer->location }}
                                    </p>
                                </div>

                            </div>

                            <div class="grid grid-cols-3 gap-4 text-center sm:min-w-[330px]">

                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-400">
                                        Status
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-semibold capitalize
                                        {{ $printer->status === 'available' ? 'text-green-600' : 'text-red-600' }}"
                                    >
                                        {{ $printer->status }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-400">
                                        Queue
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-slate-900">
                                        {{ $printer->queue_count }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-400">
                                        Wait
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-slate-900">
                                        {{ $printer->estimated_wait_minutes }} mins
                                    </p>
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        <div class="space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-bold text-slate-900">
                    System Status
                </h2>

                <div class="mt-6 space-y-5">

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">
                            Printer Service
                        </span>

                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            Operational
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">
                            Messenger Bot
                        </span>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            Not Connected
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">
                            Database
                        </span>

                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            Connected
                        </span>
                    </div>

                </div>

            </div>

            <div class="rounded-2xl bg-slate-950 p-6 text-white shadow-sm">

                <p class="text-sm font-semibold text-blue-300">
                    Next Integration
                </p>

                <h3 class="mt-2 text-xl font-bold">
                    Connect Messenger
                </h3>

                <p class="mt-3 text-sm leading-6 text-slate-300">
                    Connect the Meta Messenger webhook so students can check printer availability and queue status directly from Facebook.
                </p>

            </div>

        </div>

    </div>

</div>