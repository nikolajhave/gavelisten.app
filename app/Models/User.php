<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the wishes for the user.
     */
    public function wishes()
    {
        return $this->hasMany(Wish::class);
    }

    /**
     * Get the connections that the user has initiated.
     */
    public function sentConnections()
    {
        return $this->hasMany(Connection::class, 'user_id');
    }

    /**
     * Get the connections that the user has received.
     */
    public function receivedConnections()
    {
        return $this->hasMany(Connection::class, 'connected_user_id');
    }

    /**
     * Get all users that this user is connected to (with accepted status).
     */
    public function connections()
    {
        return $this->belongsToMany(User::class, 'connections', 'user_id', 'connected_user_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    /**
     * Get all users that are connected to this user (with accepted status).
     */
    public function connectedBy()
    {
        return $this->belongsToMany(User::class, 'connections', 'connected_user_id', 'user_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    /**
     * Get all connected users (both ways, with accepted status).
     */
    public function allConnections()
    {
        return $this->connections->merge($this->connectedBy);
    }
}
