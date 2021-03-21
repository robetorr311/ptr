@extends('layouts.admin')

@section('content')

    <h1>Pet Drivers</h1>

    <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Avatar</th>
            <th scope="col">Phone</th>
            <th scope="col">Email</th>
            <th scope="col">Verefied</th>
            <th scope="col">Vehicles</th>
            <th scope="col">Drivers Licence</th>
            <th scope="col">Ban</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)

            <tr style="border-bottom: solid 1px gray;">
                <td>{{$driver['id']}}</td>
                <td>{{$driver['name']}}</td>
                <td>
                    <div class="person">
                    <div class="avatar" style="background-image:url({{ $driver->avatar }}); height: 50px; background-size:contain; background-repeat:no-repeat">
                    </div>
                    </div>
                </td>
                <td>{{$driver['phone']}}</td>
                <td>{{$driver['email']}}</td>
                <td>{{$driver['verified']}}</td>
                <td>
                    <!-- <select> -->
                    @foreach($driver->vehiclePhotos as $photo) 
                    <!-- <option> -->
                        <div class="person">
                        <div class="avatar" style="background-image:url({{ $photo->photo }}); height: 50px; background-size:contain; background-repeat:no-repeat">
                        </div>
                        </div>
                    <!-- </option> -->
                    @endforeach
                    <!-- </select> -->
                </td>
                <td>
                    <div class="avatar" style="background-image:url({{ $driver->driverDetails->drivers_licence }}); height: 50px; background-size:contain; background-repeat:no-repeat">
                </td>
                <td>
                <form method="POST" action="/admin/users/{{$driver->id}}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <div class="form-group">
                    <input type="submit" class="btn btn-danger delete-user" value="Ban User">
                </div>
            </form>
            @endforeach
        </tbody>
</table>

@endsection