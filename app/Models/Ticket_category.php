<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket_category extends Model
{
    use HasFactory;

    protected $table = 'ticket_categories';

    public function tickets(): hasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
