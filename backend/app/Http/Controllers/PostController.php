<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::query()->where('user_id', auth()->id())->latest()->get();
        return PostResource::collection($posts);
    }

    public function store(StoreRequest $request): PostResource
    {
        $data = $request->validated();

        $imageId = $data['image_id'];
        unset($data['image_id']);

        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $post = Post::query()->create($data);

            if (isset($imageId)) {
                $this->processImage($imageId, $post->id);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }

        return new PostResource($post);
    }

    private function processImage(int $imageId, int $postId): void
    {
            $image = PostImage::query()->find($imageId);
            $image->update([
                'post_id' => $postId
            ]);
            PostImage::clearStorage();
    }
}
