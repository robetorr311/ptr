@extends('layouts.admin')

@section('content')

    <h1>Transports</h1>

    <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Status</th>
            <th scope="col">User</th>
            <th scope="col">Arrival Date</th>
            <th scope="col">Arrival Type</th>
            <th scope="col">Biddable</th>
            <th scope="col">Departure Addres</th>
            <th scope="col">Departure Date</th>
            <th scope="col">Destination Addres</th>
            <th scope="col">First bid buy</th>
            <th scope="col">Bugdet</th>
            <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transportations as $trans)
            <tr>
                <td>{{$trans['id']}}</td>
                <td>{{$trans['status']}}</td>
                <td>{{$trans['user']}}</td>
                <td>{{$trans['arrival_date']}}</td>
                <td>{{$trans['arrival_type']}}</td>
                <td>{{$trans['biddalbe']}}</td>
                <td>{{$trans['departure_address']}}</td>
                <td>{{$trans['departure_date']}}</td>
                <td>{{$trans['destination_address']}}</td>
                <td>{{$trans['first_bid_buy']}}</td>
                <td>{{$trans['budget']}}</td>
                <td>
                <form method="POST" action="/admin/transportations/{{ $trans->id }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <div class="form-group">
                    <input type="submit" class="btn btn-danger delete-user" value="Delete Transport">
                </div>
            </form>
            @endforeach
        </tbody>
</table>

@endsection