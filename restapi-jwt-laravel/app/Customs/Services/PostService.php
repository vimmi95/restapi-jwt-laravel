<?php 
namespace App\Customs\Services;
class PostService {

    /**
     * Inserting post data into the table
    */
    public function create($data) {
        $post = auth()->user()->posts()->create($data);
        return $post;
    }
}