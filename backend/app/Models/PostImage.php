<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => url('storage/'.$this->path)
        );
    }

    public static function clearStorage(): void
    {
        $images = PostImage::query()->where('user_id', auth()->id())
            ->whereNull('post_id')->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }
}
