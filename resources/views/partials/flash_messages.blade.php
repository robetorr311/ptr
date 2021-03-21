@if(Session::has('message'))
    <div class="container">
        <p class="flash">{{ Session::get('message') }}</p>
    </div>
@endif

@if(Session::has('error'))
    <div class="container">
        <p class="flash-error">{{ Session::get('error') }}</p>
    </div>
@endif

@if($errors->count() > 0)
    <div class="container">
    @foreach ($errors->all() as $error)
        <p class="flash-error">{{ $error }}</p>
    @endforeach
    </div>
@endif