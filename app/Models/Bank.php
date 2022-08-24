<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bank extends Model
{
    protected $table = 'banks';

    protected $fillable = [
        'username',
        'password',
        'imei',
        'account_number',
        'token',
        'stkname',
        'status'
    ];

    public function transfers(): BelongsTo
    {
        return $this->belongsTo(HistoryTransfer::class);
    }
}
