<?php

namespace App\Models;

use App\Models\Traits\DateTimeTrait;
use Illuminate\Database\Eloquent\Model;

class HistoryTransfer extends Model
{
    use DateTimeTrait;

    protected $table = 'history_transfers';

    protected $fillable = [
        'trans_id',
        'system_id',
        'amount',
        'receiver_account',
        'account_number',
        'callback_url',
        'receiver_bank_id',
        'reason',
        'otp',
        'status',
        'message',
        'bank_id'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
