<?php

namespace App;

use App\Services\BidServices\BidService;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use function Sodium\crypto_box_publickey_from_secretkey;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_token', 'verified', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole($role)
    {
        $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function transports()
    {
        return $this->hasMany(Transport::class);
    }

    public function driverDetails()
    {
        return $this->hasOne(DriverDetails::class);
    }

    public function vehiclePhotos()
    {
        return $this->hasMany(VehiclePhoto::class);
    }

    public function driverLicence()
    {
        return $this->hasMany(DriverLicence::class);
    }

    public function addVehiclePhoto(VehiclePhoto $photo)
    {
        return $this->vehiclePhotos()->save($photo);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function acceptedBids()
    {
        return $this->bids()->where('status', BidService::STATUS_COMPLETED);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'receiver_id', 'id');
    }

    public function reviewOverall()
    {
        /** @var Review $review */
        $i = 0;
        $driver = 0;
        $communication = 0;
        $vehicle = 0;
        $overall = 0;

        foreach ($this->receivedReviews as $review) {
            $i++;
            $driver += $review->driver;
            $communication += $review->communication;
            $vehicle += $review->vehicle;
        }

        if ($i > 0) {
            $driver = round($driver/$i, 2);
            $communication = round($communication/$i, 2);
            $vehicle = round($vehicle/$i, 2);
            $overall = round(($vehicle + $communication + $driver)/3, 2);
        }

        return [
            'driver' => $driver,
            'communication' => $communication,
            'vehicle' => $vehicle,
            'overall' => ceil($overall),
        ];
    }

    public function givenReviews()
    {
        return $this->hasMany(Review::class, 'sender_id', 'id');
    }
}
