<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aspiration extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'description',
        'status',
        'response',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isMenunggu(): bool
    {
        return $this->status === 'Menunggu';
    }
}
