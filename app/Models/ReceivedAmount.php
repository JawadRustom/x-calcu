<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivedAmount extends Model
{
    /** @use HasFactory<\Database\Factories\ReceivedAmountFactory> */
    use HasFactory;
    protected $fillable = [
        'invoice_value',
        'invoice_date',
        'operation_id',
    ];
    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
