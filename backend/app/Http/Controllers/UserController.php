<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\SubscriberFollowing;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserController extends Controller
{
    public function index(): ResourceCollection
    {
        $users = User::query()->whereNot('id', auth()->id())->get();

        $followingIds = SubscriberFollowing::query()->where('subscriber_id', auth()->id())
            ->get('following_id')->pluck('following_id')->toArray();

        foreach ($users as $user) {
            if (in_array($user->id, $followingIds)) {
                $user->is_followed = true;
            }
        }

        return UserResource::collection($users);
    }

    public function post(User $user): ResourceCollection
    {
        $posts = $user->posts()->latest()->get();
        $posts = $this->prepareLikedPosts($posts);

        return PostResource::collection($posts);
    }

    public function toggleFollowing(User $user): array
    {
        $res = auth()->user()->followings()->toggle($user->id);

        $data['is_followed'] = count($res['attached']) > 0;

        return $data;
    }

    public function followingPost(): ResourceCollection
    {
        $followedIds = auth()->user()->followings()->latest()->get()->pluck('id')->toArray();
        $likedPostIds = LikedPost::query()->where('user_id', auth()->id())
            ->get('post_id')->pluck('post_id')->toArray();
        $posts = Post::query()->whereIn('user_id', $followedIds)
            ->whereNotIn('id',$likedPostIds)->get();



        return PostResource::collection($posts);
    }

    private function prepareLikedPosts($posts)
    {
        $likedPostIds = LikedPost::query()->where('user_id', auth()->id())
            ->get('post_id')->pluck('post_id')->toArray();

        foreach ($posts as $post) {
            if (in_array($post->id, $likedPostIds)) {
                $post->is_liked = true;
            }
        }

        return $posts;
    }

}
