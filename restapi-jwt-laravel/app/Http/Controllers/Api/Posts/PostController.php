<?php

namespace App\Http\Controllers\Api\Posts;

use App\Customs\Services\PostService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostService $post) {}

    public function store(CreatePostRequest $request){
        try {
            $validatedData = $request->validated();
            $post = $this->post->create($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'post' => $post
            ], status:201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Post creation failed, please try again later'
            ], status: 500);
        }
    }


    public function update(CreatePostRequest $request ,Post $post ) {
        try {
            $validatedData = $request->validated();
            $post = $this->post->updatePost($post, $validatedData);
            return response()->json([
                'status' => 'Success',
                'message' => 'Post updated successfully',
                'data' => $post,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Post update failed, please try again later',
            ], 500);
        }
    }

}
