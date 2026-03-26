<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentNotification extends Model
{
    protected $fillable = [
        'target',
        'user_id',
        'title',
        'body',
        'meta',
        'channel'
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
