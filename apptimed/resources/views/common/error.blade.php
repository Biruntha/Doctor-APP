<h1>Some thing went wrong</h1>
@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger fade show">{{ $error }}</div>
    @endforeach
@endif
@if ($message = Session::get('message'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif
@if ($error = Session::get('error'))
    <div class="alert alert-danger">
        <p>{{ $error }}</p>
    </div>
@endif
