<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $withCount = ['comments'];
    protected $with = ['image', 'repostedPost'];

    public function image(): HasOne
    {
        return $this->hasOne(PostImage::class)->whereNotNull('post_id');
    }

    public function date(): Attribute
    {
        return Attribute::make(
          get: fn() => $this->created_at->diffForHumans()
        );
    }


    public function likedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'liked_posts', 'post_id', 'user_id');
    }

    public function repostedPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'reposted_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
