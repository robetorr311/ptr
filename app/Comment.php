<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['user_id', 'transport_id', 'content'];

    protected $with = ['user'];

    public function transport()
    {
        return $this->belongsTo(Transport::class, 'transport_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
