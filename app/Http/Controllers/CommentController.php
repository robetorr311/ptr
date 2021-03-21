<?php

namespace App\Http\Controllers;

use App\Events\Comment\CommentPosted;
use App\Http\Requests\PostCommentRequest;
use App\Services\CommentServices\CommentService;
use App\Transport;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function postComment(PostCommentRequest $request, Transport $transport, CommentService $service)
    {
        $userId = auth()->user()->id;

        $response  = $service->postComment($transport->id, $userId, $request->get('content'));

        event(new CommentPosted($transport));

        return response()->json($response['data'], $response['code']);
    }
}
