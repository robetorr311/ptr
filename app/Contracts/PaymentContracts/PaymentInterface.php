<?php
namespace App\Contracts\PaymentContracts;

use App\User;

interface PaymentInterface
{
    public function deposit($data, User $user);

    public function payout($stripe_id, $amount);
}