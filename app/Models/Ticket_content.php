<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket_content extends Model
{
    protected $table = 'ticket_contents';

    use HasFactory;

    public function User(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function Ticket(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'ticket_id');
    }
}
