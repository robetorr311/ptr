@extends('layouts.admin')

@section('content')

    <h1>Application Users</h1>

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
            @foreach($users as $user)
            <tr>
                <td>{{$user['id']}}</td>
                <td>{{$user['name']}}</td>
                <td>
                    <div class="person">
                    <div class="avatar" style="background-image:url({{ $user['avatar'] }})">
                    </div>
                    </div>
                </td>
                <td>{{$user['phone']}}</td>
                <td>{{$user['email']}}</td>
                <td>{{$user['verified']}}</td>
                <td>
                <form method="POST" action="/admin/users/{{$user->id}}">
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