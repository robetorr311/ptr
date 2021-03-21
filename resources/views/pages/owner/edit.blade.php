@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    <owner-transport-edit
        :transport="{{ $transport }}"
        transport-endpoint="{{ $transportEndpoint }}"
    ></owner-transport-edit>
@endsection