<?php

namespace App\Livewire;

use App\Models\Printer;
use Livewire\Component;

class PrinterManager extends Component
{
    public ?int $editingPrinterId = null;
    public string $name = '';
    public string $location = '';
    public string $status = 'available';
    public string $paper_status = 'available';
    public string $toner_status = 'good';
    public int $queue_count = 0;
    public int $estimated_wait_minutes = 0;
    public string $issue_reason = '';
    public string $notes = '';
    public bool $is_active = true;

    public function edit(int $printerId): void
    {
        $printer = Printer::findOrFail($printerId);

        $this->editingPrinterId = $printer->id;
        $this->name = $printer->name;
        $this->location = $printer->location ?? '';
        $this->status = $printer->status;
        $this->paper_status = $printer->paper_status;
        $this->toner_status = $printer->toner_status;
        $this->queue_count = $printer->queue_count;
        $this->estimated_wait_minutes = $printer->estimated_wait_minutes;
        $this->issue_reason = $printer->issue_reason ?? '';
        $this->notes = $printer->notes ?? '';
        $this->is_active = $printer->is_active;
    }

    public function update(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:available,unavailable,maintenance'],
            'paper_status' => ['required', 'in:available,low,empty'],
            'toner_status' => ['required', 'in:good,low,empty'],
            'queue_count' => ['required', 'integer', 'min:0'],
            'estimated_wait_minutes' => ['required', 'integer', 'min:0'],
            'issue_reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $printer = Printer::findOrFail($this->editingPrinterId);

        $printer->update([
            'name' => $this->name,
            'location' => $this->location ?: null,
            'status' => $this->status,
            'paper_status' => $this->paper_status,
            'toner_status' => $this->toner_status,
            'queue_count' => $this->queue_count,
            'estimated_wait_minutes' => $this->estimated_wait_minutes,
            'issue_reason' => $this->issue_reason ?: null,
            'notes' => $this->notes ?: null,
            'is_active' => $this->is_active,
        ]);

        $this->cancelEdit();

        session()->flash('success', 'Printer updated successfully.');
    }

    public function cancelEdit(): void
    {
        $this->editingPrinterId = null;
        $this->name = '';
        $this->location = '';
        $this->status = 'available';
        $this->paper_status = 'available';
        $this->toner_status = 'good';
        $this->queue_count = 0;
        $this->estimated_wait_minutes = 0;
        $this->issue_reason = '';
        $this->notes = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.printer-manager', [
            'printers' => Printer::orderBy('name')->get(),
        ]);
    }
}