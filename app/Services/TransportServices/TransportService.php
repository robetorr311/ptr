<?php
namespace App\Services\TransportServices;

use App\Bid;
use App\Contracts\TransportContracts\TransportServiceInterface;
use App\Pet;
use App\Review;
use App\Services\BidServices\BidService;
use App\Transport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RandomLib\Factory;

class TransportService implements TransportServiceInterface
{
    public function createTransport($data)
    {

        $transport = Transport::create($this->mapData($data));

        $this->insertPets($transport, $data['pet_ids']);

        return $transport;

    }

    public function updateTransport($data, $id) 
    {

        $transport = Transport::findOrFail($id);

        $transport->arrival_type = $data['arrival_type'];
        $transport->arrival_date = Carbon::createFromFormat('m/d/Y', $data['arrival_date']);
        $transport->departure_type = $data['departure_type'];
        $transport->departure_date = Carbon::createFromFormat('m/d/Y', $data['departure_date']);


        $transport->save();

        return $transport;

    }

    private function mapData($data)
    {
        return [
            'user_id' => $data['user_id'],
            'arrival_date' => Carbon::createFromFormat('m/d/Y', $data['arrival_date']),
            'arrival_type' => $data['arrival_type'],
            'biddable' => $data['biddable'],
            'departure_address' => $data['departure_address'],
            'departure_date' => Carbon::createFromFormat('m/d/Y', $data['departure_date']),
            'departure_lat' => $data['departure_lat'],
            'departure_lng' => $data['departure_lng'],
            'departure_type' => $data['departure_type'],
            'destination_address' => $data['destination_address'],
            'destination_lat' => $data['destination_lat'],
            'destination_lng' => $data['destination_lng'],
            'first_bid_buy' => $data['first_bid_buy'],
            'budget' => (int)$data['budget']
        ];
    }

    private function insertPets(Transport $transport, $pet_ids)
    {
        foreach ($pet_ids as $key => $value) {
            $pet = Pet::find($value);
            if (isset($pet)) {
                $transport->addPet($pet);
            }
        }

    }

    public function getTransports($data = null)
    {
        if (!isset($data))
            return Transport::where('status', Transport::STATUS_OPEN)->paginate(10);

        $transports = Transport::query();
        $transports = $transports->where('status', Transport::STATUS_OPEN);

        if (isset($data['pet_type'])) {
            $pet_type = $data['pet_type'];
            $transports = $transports->whereHas('pets', function ($query) use ($pet_type) {

                $query->where('type', 'like' , '%'.$pet_type .'%');

            });
        }

        if (isset($data['pet_weight'])) {
            $pet_size = (int)$data['pet_weight'];
            $transports = $transports->whereHas('pets', function ($query) use ($pet_size) {

                $query->where('weight', '<' , $pet_size);

            });
        }

        if (isset($data['start_address']))
            $transports = $transports->where('departure_address', 'like', '%' . $data['start_address'] . '%');

        if (isset($data['start_price']))
            $transports = $transports->where('budget', '>=', (int)$data['start_price']);

        if (isset($data['end_price']))
            $transports = $transports->where('budget', '<=', (int)$data['end_price']);

        if (isset($data['biddable']))
            $transports = $transports->where('biddable', (int)$data['biddable']);


        if (isset($data['start_address']))
            $transports = $transports->where('departure_address', 'like', '%' . $data['start_address'] . '%');

        if (isset($data['destination_address']))
            $transports = $transports->where('destination_address', 'like', '%' . $data['destination_address'] . '%');





        return $transports->paginate(10);
    }

    public function getTransportsByRadius($data)
    {

        $transports = Transport::query()->whereDate('departure_date', '>=', Carbon::now());

        if (isset($data['startDate']))
            $transports = $transports->whereDate('departure_date', '>=', Carbon::createFromFormat('m/d/Y', $data['startDate']));

        if (isset($data['endDate']))
            $transports = $transports->whereDate('arrival_date', '<=', Carbon::createFromFormat('m/d/Y', $data['endDate']));

        if (isset($data['petType'])) {
            $pet_type = $data['petType'];
            $transports = $transports->whereHas('pets', function ($query) use ($pet_type) {

                $query->where('type', 'like' , '%'.$pet_type .'%');

            });
        }
        if (isset($data['petWeight'])) {
            $pet_size = (int)$data['petWeight'];
            $transports = $transports->whereHas('pets', function ($query) use ($pet_size) {

                $query->where('weight', '<' , $pet_size);

            });
        }
        if (isset($data['amountMin']))
            $transports = $transports->where('budget', '>=', (int)$data['amountMin']);

        if (isset($data['amountMax']))
            $transports = $transports->where('budget', '<=', (int)$data['amountMax']);

        $transports = $transports->where('status', Transport::STATUS_OPEN)->get();

        $result = [];

        if (isset($data['lat'], $data['lng'], $data['radius'])) {
            $lat = $data['lat'];
            $lng = $data['lng'];
            $radius = $data['radius'];
            foreach($transports as $transport) {

                if ($this->getDistance($lat, $lng, $transport->departure_lat, $transport->departure_lng) <= (float)$radius
                || $this->getDistance($lat, $lng, $transport->destination_lat, $transport->destination_lng) <= (float)$radius) {
                    $result[] = $transport;
                }
            }
        } else {
            foreach($transports as $transport) {
                $result[] = $transport;
            }
        }
        return $result;
    }


    private function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {

        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return $d;

    }

    /**
     * @param $data
     * @param Bid $bid
     * @throws \Exception
     */
    public function finishTransport($data, Bid $bid){

        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        $payout_code = strtoupper($generator->generateString(6));

        DB::beginTransaction();

        try {
            $data['sender_id'] = auth()->user()->id;
            $data['receiver_id'] = $bid->user_id;
            $review = Review::create($data);

            $bidService = new BidService();

            if (!$bidService->finishBid($bid, $payout_code))
                throw new \Exception('Bid could not be completed');

            $bid = $bid->fresh();


            /** @var Transport $transport */
            $transport = $bid->transport;

            $transport->status = Transport::STATUS_COMPLETED;

            if (!$transport->save())
                throw new \Exception('Transport could not be completed');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $bid;
    }

    /**
     * @param Transport $transport
     * @return mixed
     * @throws \Exception
     */
    public function getPaycode(Transport $transport)
    {
        /** @var Bid $bid */
        foreach ($transport->bids as $bid){
            if ($bid->status == BidService::STATUS_COMPLETED && isset($bid->payout_code)) {
                return $bid->payout_code;
            }
        }

        throw new \Exception('Payment code not found');
    }


}