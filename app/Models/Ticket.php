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
        return $this->belongsToMany(User::class, 'assigned', 'ticket_id', 'user_id');
    }
    public static function add_content($ticket_id, $user_id, $content_type, $text): bool
    {
        $ticket_content = new Ticket_content();
        $ticket_content->ticket_id = $ticket_id;
        if ($content_type && $text) {
            $ticket_content->type = $content_type;
            $ticket_content->text = $text;
            $ticket_content->user_id = $user_id;
            $ticket_content->save();
            return true;
        } else {
            return false;
        }
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

    /* public static function get_content($ticket_id): array
    {
        $ticket_content = Ticket_content::where('ticket_id', $ticket_id)->get();
        return $ticket_content->toArray();
    }*/
    public static function delete_content($ticket_id, $content_id): bool
    {
        $ticket_content = Ticket_content::where('id', $content_id)->where('ticket_id', $ticket_id)->first();
        if ($ticket_content) {
            $ticket_content->delete();
            return true;
        } else {
            return false;
        }
    }
}
