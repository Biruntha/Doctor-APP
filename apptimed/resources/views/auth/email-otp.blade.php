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
            <div class="d-flex bg-white shadow-sm w-auto m-auto p-2 p-md-5" style="align-items: center">
                <div class="w-auto text-end d-none d-md-block">
                    <img src="/assets/images/login.png" class="img-fluid" style="object-fit:contain" />
                </div>
                <div style="" class="text-center;">
                    <form method="POST" action="{{ route('login-dash') }}">
                        <div class="modal-body">
                            <div class="h5 modal-title">
                                <h4 class="mb-4">
                                    OTP Verification
                                </h4>
                                @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block mb-3" style="max-width:430px">
                                    <strong style="font-size: 0.9rem;">{{ $message }}</strong>
                                </div>
                                @elseif ($message = Session::get('message'))
                                <div class="alert alert-success alert-block mb-3" style="max-width:430px">
                                    <strong style="font-size: 0.9rem;">{{ $message }}</strong>
                                </div>
                                @endif
                            </div>

                            @csrf
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label>OTP that you received via email</label>
                                            <input id="email-otp" type="number" class="form-control" name="otp" value="" required autofocus>   
                                            <input type="hidden" value="{{$token}}" name="utoken" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="clearfix px-3">
                            <div class="float-right">
                                    <button type="submit" class="btn btn-primary w-100 mx-1  p-2">Verify</button>
                            </div>
                        </div>
                        <div class="row px-3">
                            <div class="col-12 mt-4">
                                <a class="btn btn-light w-100 mx-1  p-2" href="/login">Take me to the Login screen again</a>
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
