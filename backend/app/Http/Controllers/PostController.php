<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CommentRequest;
use App\Http\Requests\Post\RepostRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Comment;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(): ResourceCollection
    {
        $posts = Post::query()->where('user_id', auth()->id())
            ->withCount('repostedByPosts')->latest()->get();

        $likedPostIds = LikedPost::query()->where('user_id', auth()->id())
            ->get('post_id')->pluck('post_id')->toArray();

        foreach ($posts as $post) {
            if (in_array($post->id, $likedPostIds)) {
                $post->is_liked = true;
            }
        }

        return PostResource::collection($posts);
    }

    public function store(StoreRequest $request): PostResource|JsonResponse
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
            PostImage::clearStorage();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }

        return new PostResource($post);
    }

    public function repost(RepostRequest $request, Post $post): void
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['reposted_id'] = $post->id;

        Post::query()->create($data);
    }

    public function comment(CommentRequest $request, Post $post): CommentResource
    {
        $data = $request->validated();

        $data['post_id'] = $post->id;
        $data['user_id'] = auth()->id();

        $comment = Comment::query()->create($data);

        return new CommentResource($comment);
    }

    public function commentList(Post $post): ResourceCollection
    {
        $comments = $post->comments()->latest()->get();

        return CommentResource::collection($comments);
    }

    public function toggleLike(Post $post): array
    {
        $res = auth()->user()->likedPosts()->toggle($post->id);

        $data['is_liked'] = count($res['attached']) > 0;
        $data['likes_count'] = $post->likedUsers()->count();

        return $data;
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
