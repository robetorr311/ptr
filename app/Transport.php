<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    const STATUS_CLOSED = 'closed';
    const STATUS_OPEN = 'open';
    const STATUS_CANCELED = 'canceled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PROGRESS = 'in-progress';
    const STATUS_CASHED = 'cashed';
    const STATUS_AWAITING = 'awaiting';

    protected $table = 'transports';

    protected $with = ['pets', 'owner', 'bids'];

    protected $fillable = [
        'user_id',
        'arrival_date',
        'arrival_type',
        'biddable',
        'departure_address',
        'departure_date',
        'departure_lat',
        'departure_lng',
        'departure_type',
        'destination_address',
        'destination_lat',
        'destination_lng',
        'first_bid_buy',
        'budget'
    ];

    protected $appends = ['departure_date_format', 'arrival_date_format'];

    public function getDepartureDateFormatAttribute()
    {
        return $this->formatDate($this->attributes['departure_date']);
    }

    public function getArrivalDateFormatAttribute()
    {
        return $this->formatDate($this->attributes['arrival_date']);
    }

    private function formatDate($dateString)
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
        return [
            'day' => $date->format('d'),
            'month' => $date->format('M'),
            'year' => $date->format('Y'),
            'full' => $date->toFormattedDateString(),
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'transport_pet');
    }

    public function addPet(Pet $pet)
    {
        return $this->pets()->save($pet);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)->with('user.driverDetails')->orderBy('created_at', 'DESC');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'transport_id');
    }
}
