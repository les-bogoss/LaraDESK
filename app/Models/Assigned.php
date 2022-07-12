<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assigned extends Model
{
    use HasFactory;

    protected $table = 'assigned';

    public function ticket(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Ticket::class, 'tickets');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'users');
    }
}
