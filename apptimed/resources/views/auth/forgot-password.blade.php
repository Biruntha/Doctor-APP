@extends("layouts.main-web")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Reset Password
</h1>
@endsection

@section("main-body")
<div class="app-container mt-5">
    
    <div class="row" style="">
        <div class="col-12 col-xl-4 offset-xl-4 col-md-6 offset-md-3">
            <div class="bg-white shadow-sm p-2 py-3 rounded" style="align-items: center">
                <div style="" class="text-center;">
                    @if(!isset($token))
                    <form method="POST" action="{{ route('forgot-password') }}">
                        <div class="modal-body">
                            <!-- <div class="h5 modal-title">
                                @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block mb-3" style="">
                                    <strong style="font-size: 0.9rem;">{{ $message }}</strong>
                                </div>
                                @endif
                            </div> -->

                            @csrf
                                <div class="form-row" style="min-width:250px;">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group"><label>Email</label>
                                        <input id="email" type="email" placeholder="Enter your account email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="row p-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary  w-100 mx-1  p-2">NEXT</button>
                            </div>
                                <div class="col-12 mt-2">
                                    <a class="btn btn-light w-100 mx-1 mt-3 p-2" href="/login">Take me to the Login Screen</a>
                                </div>
                        </div>
                    </form>
                    @else
                    <form method="POST" action="{{ route('forgot-password') }}">
                        <div class="modal-body">
                            <div class="h5 modal-title">
                                <h4 class="mb-4">
                                    Enter your new password
                                </h4>
                                @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block mb-3" style="">
                                    <strong style="font-size: 80%">{{ $message }}</strong>
                                </div>
                                @endif
                            </div>

                            @csrf
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group"><label>Password</label><input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="position-relative form-group d-none"><label>Confirm Password</label><input id="password2" type="password" class="form-control @error('password') is-invalid @enderror" name="password2" autocomplete="off">
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        @if(isset($token))
                                        <input type="hidden" value="{{$token}}" name="token" id="token"/>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        <div class="row p-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary  w-100 mx-1  p-2">SUBMIT</button>
                                <div class="col-12 mt-2">
                                    <a class="btn btn-light w-100 mx-1  p-2" href="/login">Take me to the Login Screen</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>

</script>  
@endsection
