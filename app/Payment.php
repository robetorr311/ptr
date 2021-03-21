<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['user_id', 'bid_id', 'amount', 'type', 'transaction_id'];

    protected $with = ['user', 'bid'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bid()
    {
        return $this->belongsTo(Bid::class, 'bid_id');
    }
}
