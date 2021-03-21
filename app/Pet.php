<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pets';

    protected $fillable = ['user_id', 'name', 'type', 'size', 'weight'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function transports()
    {
        return $this->belongsToMany(Transport::class, 'transport_pet');
    }
}
