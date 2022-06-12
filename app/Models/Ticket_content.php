<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket_content extends Model
{
    use HasFactory;
    public function User(): HasOne
    {
        return $this->hasOne(User::class);
    }
    public function Ticket(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
