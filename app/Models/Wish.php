<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'link',
        'user_id',
    ];

    /**
     * Get the user that owns the wish.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
