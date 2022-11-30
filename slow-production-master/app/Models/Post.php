<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_likes')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
