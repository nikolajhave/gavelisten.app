<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'connected_user_id',
        'status',
    ];

    /**
     * Get the user that initiated the connection.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that was connected to.
     */
    public function connectedUser()
    {
        return $this->belongsTo(User::class, 'connected_user_id');
    }
}
