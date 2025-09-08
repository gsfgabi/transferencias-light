<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'payee_id',
        'amount',
        'status',
        'error_message',
    ];

    /**
     * Get the sender user for the transaction.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the payee user for the transaction.
     */
    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}