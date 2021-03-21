<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bid extends Model
{
    use SoftDeletes;

    protected $fillable = ['amount', 'status', 'user_id', 'transport_id'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function transport()
    {
        return $this->belongsTo(Transport::class, 'transport_id');
    }
}
