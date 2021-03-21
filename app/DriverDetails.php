<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverDetails extends Model
{

    protected $fillable = [
        'user_id', 'vehicle_type', 'drivers_licence'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
