<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_type',
        'reference',
        'application_id',
        'applicable_id',
        'user_id',
        'company_id',
        'invoice_number',
        'description',
        'cust_name',
        'cust_address',
        'cust_phone',
        'cust_email',
        'callback_url',
        'cancel_url',
        'notify_url',
        'checkout_items',
        'payment_code',
        'payment_data',
        'method',
        'amount',
        'app_fee',
        'platform_fee',
        'currency',
        'status',
        'gateway_transaction_id',
        'payment_url',
        'payment_url_expiry',
        'payment_complete_response',
        'payment_complete_datetime',
        'paid_at',
        'reconciled_at'
    ];

    protected $hidden = [];

    protected $casts = [];

}
