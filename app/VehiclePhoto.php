<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehiclePhoto extends Model
{
    protected $fillable = ['user_id', 'photo'];

    protected $appends = ['photoUrl'];

    public function getPhotoUrlAttribute()
    {
        return asset($this->attributes['photo']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
