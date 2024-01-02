<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $with = ['image'];

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
}
