<?php

namespace Database\Seeders;

use App\Models\Printer;
use Illuminate\Database\Seeder;

class PrinterSeeder extends Seeder
{
    public function run(): void
    {
        Printer::create([
            'name' => 'Printer 1',
            'location' => 'Printing Area',
            'status' => 'available',
            'paper_status' => 'available',
            'toner_status' => 'good',
            'queue_count' => 2,
            'estimated_wait_minutes' => 5,
            'is_active' => true,
        ]);

        Printer::create([
            'name' => 'Printer 2',
            'location' => 'Printing Area',
            'status' => 'unavailable',
            'paper_status' => 'empty',
            'toner_status' => 'good',
            'queue_count' => 0,
            'estimated_wait_minutes' => 0,
            'issue_reason' => 'Paper Jam',
            'is_active' => true,
        ]);

        Printer::create([
            'name' => 'Printer 3',
            'location' => 'Printing Area',
            'status' => 'available',
            'paper_status' => 'available',
            'toner_status' => 'low',
            'queue_count' => 8,
            'estimated_wait_minutes' => 20,
            'is_active' => true,
        ]);
    }
}