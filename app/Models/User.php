<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    protected $table = 'users';

    use HasApiTokens, HasFactory, Notifiable;

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'roles_users_join', 'user_id', 'role_id');
    }

    public function assignedTicket(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'assigned', 'ticket_id', 'user_id');
    }

    public function ticket(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function hasPerm($permission): bool
    {
        // foreach role check if it contains the permission
        foreach ($this->roles()->get() as $role) {
            if ($role->permissions()->where('name', $permission)->first()) {
                return true;
            }
        }

        return false;
    }

    public function hasRole($role): bool
    {
        // foreach role check if it contains the role

        if ($this->roles()->get()->contains('name', $role)) {
            return true;
        } else {
            return false;
        }
    }

    public function permissions()
    {
        $permissions = [];
        // foreach role check if it contains the permission
        foreach ($this->roles()->get() as $role) {
            foreach ($role->permissions()->get() as $permission) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }




    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'api_token',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
