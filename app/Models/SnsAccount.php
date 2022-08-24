<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SnsAccount extends Model
{
    /** @var string $table */
    protected $table = 'sns_accounts';

    /** @var array $fillable */
    protected $fillable = [
        'user_id',
        'sns',
        'sns_id',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
