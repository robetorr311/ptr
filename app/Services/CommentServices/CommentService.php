<?php
namespace App\Services\CommentServices;

use App\Comment;
use App\Transport;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function postComment($transport_id, $user_id, $content)
    {
        DB::beginTransaction();

        try {
            $comment = Comment::create([
                'transport_id' => $transport_id,
                'user_id' => $user_id,
                'content' => $content
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'code' => 500,
                'data' => $e->getMessage()
            ];
        }
        DB::commit();

        $comment->user;
        return [
            'code' => 200,
            'data' => $comment
        ];
    }
}