<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPaymentLedger extends Model
{
    protected $table = 'customer_payment_ledger';

    protected $fillable = [
        'user_id',
        'order_id',
        'payment_transaction_id',
        'entry_type',
        'amount',
        'balance_after',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function transaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_transaction_id');
    }
}
