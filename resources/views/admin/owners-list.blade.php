@extends('layouts.admin')

@section('content')

    <h1>Pet Owners</h1>

    <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Avatar</th>
            <th scope="col">Phone</th>
            <th scope="col">Email</th>
            <th scope="col">Verefied</th>
            <th scope="col">Ban</th>
            </tr>
        </thead>
        <tbody>
            @foreach($owners as $owner)
            <tr>
                <td>{{$owner['id']}}</td>
                <td>{{$owner['name']}}</td>
                <td>
                    <div class="person">
                    <div class="avatar" style="background-image:url({{ $owner->avatar }}); height: 50px; background-size:contain; background-repeat:no-repeat">
                    </div>
                    </div>
                </td>
                <td>{{$owner['phone']}}</td>
                <td>{{$owner['email']}}</td>
                <td>{{$owner['verified']}}</td>
                <td>
                <form method="POST" action="/admin/users/{{$owner->id}}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <div class="form-group">
                    <input type="submit" class="btn btn-danger delete-user" value="Ban User">
                </div>
            </form>
            </td>
            @endforeach
        </tbody>
</table>

@endsection