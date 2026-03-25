<?php 
namespace App\Customs\Services;

use App\Models\Post;

class PostService {

    /**
     * Inserting post data into the table
    */
    public function create(array $data) {
        $post = auth()->user()->posts()->create($data);
        return $post;
    }

    /**
     * Updated the post data into the table
    */
    public function updatePost(Post $post, array $data) {
        if($post->user_id !== auth()->user()->id)
            {
                throw new \Exception("Unauthorized User - No Permission to update the post");
            }
        $post->update($data);
        return $post;
    }
}