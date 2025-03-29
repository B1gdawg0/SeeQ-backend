<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Queue extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'current_queue',
        'is_available',
        'tag',
        'shop_id',
    ];

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_queues')->withPivot('queue_number');
    }

    public function shop() : BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
