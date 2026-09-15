<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'status',
        'paper_status',
        'toner_status',
        'queue_count',
        'estimated_wait_minutes',
        'issue_reason',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'queue_count' => 'integer',
            'estimated_wait_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}