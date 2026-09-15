<?php

namespace App\Livewire;

use App\Models\Printer;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        $printers = Printer::orderBy('name')->get();

        return view('livewire.admin-dashboard', [
            'printers' => $printers,
            'totalPrinters' => $printers->count(),
            'availablePrinters' => $printers->where('status', 'available')->count(),
            'unavailablePrinters' => $printers->whereIn('status', ['unavailable', 'maintenance'])->count(),
            'totalQueue' => $printers->sum('queue_count'),
            'averageWait' => round($printers->avg('estimated_wait_minutes') ?? 0),
        ]);
    }
}