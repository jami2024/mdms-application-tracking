<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'reference', 'application_id', 'user_id', 'description', 'method',
        'amount', 'currency', 'status', 'gateway_transaction_id', 'paid_at', 'reconciled_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'reconciled_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
