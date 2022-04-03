<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public function items() : HasMany
    {
        return $this->hasMany(Item::class);
    }
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }
}
