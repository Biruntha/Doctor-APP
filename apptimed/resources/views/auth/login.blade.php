@extends("layouts.main-web")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Please login first
</h1>
@endsection

@section("main-body")
<div class="app-container mt-5">
    <div class="row" style="">
        <div class="col-12 col-xl-4 offset-xl-4 col-md-6 offset-md-3">
            <div class="d-flex bg-white shadow-md p-2 py-3 p-md-5 rounded" style="align-items: center">
                <div style="" class="text-center;">
                    <form method="POST" action="{{ route('login-dash') }}">
                        <div class="modal-body">
                            <!-- <div class="h5 modal-title">
                                @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block mb-3" style="max-width:430px">
                                    <strong style="font-size: 0.9rem;">{{ $message }}</strong>
                                </div>
                                @elseif ($message = Session::get('message'))
                                <div class="alert alert-success alert-block mb-3" style="max-width:430px">
                                    <strong style="font-size: 0.9rem;">{{ $message }}</strong>
                                </div>
                                @endif
                            </div> -->

                            @csrf
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group"><label>Email</label><input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="position-relative form-group"><label>Password</label><input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="clearfix px-3">
                            <div class="float-right">
                                    <button type="submit" class="btn btn-primary w-100 mx-1  p-2">Login</button>
                            </div>
                        </div>
                        <div class="row px-3">
                            <div class="col-12 mt-2"> 
                                @if (Route::has('password.request'))
                                    <a class="btn btn-light w-100 mx-1  p-2" href="/forgot-password">
                                        {{ __('Forgot Your Password ?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-12 mt-4">
                                <a class="btn btn-primary-inverse w-100 mx-1  p-2" href="/patient/signup">Create an Account - Patient</a>
                            </div>
                            <div class="col-12 mt-3">
                                <a class="btn btn-primary-inverse w-100 mx-1  p-2 mb-2" href="/doctor/signup">Create an Account - Doctor</a>
                            </div>
                        </div>
                    </form>
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
