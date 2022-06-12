<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function ticket_category(): HasOne
    {
        return $this->hasOne(Ticket_category::class);
    }

    public function ticket_status(): HasOne
    {
        return $this->hasOne(Ticket_status::class);
    }

    public function assignedUser(): BelongsToMany
    {
        return $this->belongsToMany(User::Class);
    }
}
