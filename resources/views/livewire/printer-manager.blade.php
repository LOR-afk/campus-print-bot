<div class="min-h-screen bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Printer Management
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Monitor and update printer availability, queue, paper, toner, and waiting time.
            </p>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($printers as $printer)
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">
                                    {{ $printer->name }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $printer->location ?: 'No location assigned' }}
                                </p>
                            </div>

                            @if ($printer->status === 'available')
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Available
                                </span>
                            @elseif ($printer->status === 'maintenance')
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                    Maintenance
                                </span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Unavailable
                                </span>
                            @endif
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="flex justify-between border-b border-gray-100 pb-3">
                                <span class="text-sm text-gray-500">Paper</span>
                                <span class="text-sm font-semibold capitalize text-gray-900">
                                    {{ $printer->paper_status }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-gray-100 pb-3">
                                <span class="text-sm text-gray-500">Toner</span>
                                <span class="text-sm font-semibold capitalize text-gray-900">
                                    {{ $printer->toner_status }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-gray-100 pb-3">
                                <span class="text-sm text-gray-500">Queue</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $printer->queue_count }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Estimated Wait</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $printer->estimated_wait_minutes }} mins
                                </span>
                            </div>
                        </div>

                        @if ($printer->issue_reason)
                            <div class="mt-5 rounded-xl bg-red-50 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-red-500">
                                    Issue
                                </p>

                                <p class="mt-1 text-sm text-red-700">
                                    {{ $printer->issue_reason }}
                                </p>
                            </div>
                        @endif

                        <button
                            wire:click="edit({{ $printer->id }})"
                            class="mt-6 w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            Update Printer
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($editingPrinterId)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
                <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">

                    <div class="border-b border-gray-200 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">
                                    Update Printer
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Update current printer information.
                                </p>
                            </div>

                            <button
                                wire:click="cancelEdit"
                                class="text-2xl text-gray-400 hover:text-gray-700"
                            >
                                ×
                            </button>
                        </div>
                    </div>

                    <form wire:submit="update" class="space-y-6 p-6">

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Printer Name
                                </label>

                                <input
                                    type="text"
                                    wire:model="name"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                >

                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Location
                                </label>

                                <input
                                    type="text"
                                    wire:model="location"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Status
                                </label>

                                <select
                                    wire:model="status"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Paper Status
                                </label>

                                <select
                                    wire:model="paper_status"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="available">Available</option>
                                    <option value="low">Low</option>
                                    <option value="empty">Empty</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Toner Status
                                </label>

                                <select
                                    wire:model="toner_status"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="good">Good</option>
                                    <option value="low">Low</option>
                                    <option value="empty">Empty</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Queue Count
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    wire:model="queue_count"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Estimated Wait
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    wire:model="estimated_wait_minutes"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Issue Reason
                                </label>

                                <input
                                    type="text"
                                    wire:model="issue_reason"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Notes
                            </label>

                            <textarea
                                wire:model="notes"
                                rows="3"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            ></textarea>
                        </div>

                        <label class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                wire:model="is_active"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >

                            <span class="text-sm font-medium text-gray-700">
                                Active Printer
                            </span>
                        </label>

                        <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">
                            <button
                                type="button"
                                wire:click="cancelEdit"
                                class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                            >
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        @endif

    </div>
</div>