@extends("layouts.main-web")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Creat a Patient account
</h1>
@endsection

@section("main-body")
<form method="POST" action="{{ route('patient-register') }}">
{{ csrf_field() }}
    <div class="app-container p-2">
    <div class="row">
        <div class="col-md-8 col-sm-12 offset-md-2">
            <div class="bg-white shadow-md rounded p-3 p-md-5 mb-5 mt-4" style="align-items: center;position:relative">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-3 text-left">
                    <label for="firstname" class="form-label">First Name <strong style="color:red">*</strong></label>
                    <input class="form-control mx-1" placeholder="First Name" type="text" value="{{old('firstname')}}" name="firstname" required/>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3 text-left">
                    <label for="lastname" class="form-label">Last Name <strong style="color:red">*</strong></label>
                    <input class="form-control mx-1" placeholder="Last Name" type="text" value="{{old('lastname')}}" name="lastname" required/>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3 text-left">
                    <label for="email" class="form-label">Email <strong style="color:red">*</strong></label>
                    <input class="form-control mx-1" placeholder="Email" type="email" value="{{old('email')}}" name="email" required/>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3 text-left">
                    <label for="contact" class="form-label">Contact Number <strong style="color:red">*</strong></label>
                    <input class="form-control mx-1" placeholder="Contact Number" type="text" value="{{old('contact')}}" name="contact" required/>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3 text-left">
                        <label for="password" class="form-label">Password <strong style="color:red">*</strong></label>
                        <input class="form-control mx-1" placeholder="Password" type="password" name="password" required/>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3 text-left">
                        <label for="gender" class="form-label">Gender <strong style="color:red">*</strong></label>
                        <select class="form-control" name="gender">
                            <option value="">All</option>
                            <option {{old('gender') == "Male" ? 'selected' : ''}} value="Male">Male</option>
                            <option {{old('gender') == "Female" ? 'selected' : ''}} value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth <strong style="color:red">*</strong></label>
                        <input type="date" value="{{old('dob')}}" placeholder="Date of Birth" name="dob" class="form-control" />
                    </div>
                </div>
                <div class="col-12">
                    <div class="row clearfix px-3">
                        <div class="col-12 col-md-6 offset-md-3 mt-4">
                            <button type="submit" class="btn btn-primary w-100 mx-1  p-2">Register</button>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row px-3 mt-2">
                        <div class="col-12 col-md-6 offset-md-3">
                            <a class="btn btn-light w-100 mx-1  p-2" href="/login">Already Have An Account?</a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row px-3 mt-2">
                        <div class="col-12 col-md-6 offset-md-3 mt-4">
                            <a class="btn btn-primary-inverse w-100 mx-1  p-2" href="/doctor/signup">I'm a Doctor</a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    </div>
</form>
@endsection

@section("scripts")
<script>

</script>  
@endsection