<?php
namespace App\Contracts\TransportContracts;

use App\Bid;
use App\Transport;

interface TransportServiceInterface
{
    public function createTransport($data);

    public function updateTransport($data, $id);

    public function getPaycode(Transport $transport);

    public function getTransports($data = null);

    public function getTransportsByRadius($data);

    public function finishTransport($data, Bid $transport);

}