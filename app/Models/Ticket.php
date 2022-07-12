<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    protected $table = 'tickets';

    use HasFactory;

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function ticket_category(): HasOne
    {
        return $this->hasOne(Ticket_category::class, 'id', 'category_id');
    }

    public function ticket_status(): HasOne
    {
        return $this->hasOne(Ticket_status::class, 'id', 'status_id');
    }

    public function assignedUser(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'assigned', 'ticket_id', 'user_id');
    }

    public static function update_header($ticket_id, $title, $priority, $rating, $category_id, $status_id): bool
    {
        $ticket = Ticket::where('id', $ticket_id)->first();
        //verify if ticket exists
        if ($ticket) {
            //verify if nothing is null
            if ($title != null) {
                $ticket->title = $title;
            }
            if ($priority != null) {
                $ticket->priority = $priority;
            }
            if ($rating != null) {
                $ticket->rating = $rating;
            }
            if ($category_id != null) {
                $ticket->category_id = $category_id;
            }
            if ($status_id != null) {
                $ticket->status_id = $status_id;
            }
            $ticket->save();

            return true;
        } else {
            return false;
        }
    }

    public function ticket_content(): HasMany
    {
        return $this->hasMany(Ticket_content::class, 'ticket_id');
    }
}
