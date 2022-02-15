@extends("layouts.main-web")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Review Reservation
</h1>
@endsection

@section("main-body")
<div class="row mt-1">
<form action="{{ route('store-patient-details') }}" method="POST">
    @csrf
    <div class="col-12 col-md-6">
        <div class="mb-4 p-2 bg-white rounded shadow-md vacancy-card">
            <div class="d-flex">
                <div class="img-cont">
                    @if($data->image == null)
                    <img alt="doctor-img" src="data:image/png;base64,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" />
                    @else
                    <img alt="doctor-img" src="{{asset('storage/UserImages/'.$data->image)}}" />
                    @endif
                </div>
                <div class="details">
                    <h4>{{ $data->fullname }}</h4>
                    <p>{{ $data->specializations }}</p>
                    <p>Appointment #{{ $appointment_number->appointment_number }}</p>
                    <p>{{ $data->date == $currentDate ? 'Today' : ($data->date == $tomorrowDate ? 'Tomorrow' : $data->date)}}, Est. By {{ $next_available_time->next_available_time}}</p>
                </div>
            </div>
            <div class="details">
                <h5>Payment Details</h5>     
                <p>Doctor Fees LKR {{ $data->fees }}</p>
                <p>App Fees LKR {{ $data->app_fees }}</p>
                <p>Total LKR {{ $data->fees + $data->app_fees }}</p>
            </div>
            <div class="details" id="book_you">
                <h5>Patient Details</h5>     
                <p>Patient Name : {{ $patient->fullname }}</p>
                <p>Age : {{ $patient->age }}</p>
                <p>Gender : {{ $patient->gender }}</p>
                <a title="Booking for someone else" id="btn_book_someone_else" class="btn btn-primary">Booking for someone else? <i class="fas fa-sort-down"></i></a>
            </div>
            <div class="details" id="book_someone_else" style="display:none">
                <a title="Booking for you" id="btn_book_you" class="btn btn-primary">Booking for you? <i class="fas fa-sort-up"></i></a>
                <h5>Patient Details</h5>     
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1" value="{{old('name')}}" placeholder="Full Name" type="text" id="name" name="name"/>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="dob" class="form-label">DOB <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1" value="{{old('dob')}}" placeholder="Date of Birth" type="date" id="dob" name="dob"/>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                        <label for="gender" class="form-label">Gender <strong style="color:red">*</strong></label>
                        <select class="form-control" name="gender" id="gender">
                            <option value="">All</option>
                            <option {{old('gender') == "Male" ? 'selected' : ''}} value="Male">Male</option>
                            <option {{old('gender') == "Female" ? 'selected' : ''}} value="Female">Female</option>
                        </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="details">
                <input type="text" value="{{$patient->id}}" name="patient_id" id="patient_id"/></br>
                <input type="text" value="" name="patient_name" id="patient_name"/></br>
                <input type="text" value="" name="patient_dob" id="patient_dob"/></br>
                <input type="text" value="" name="patient_gender" id="patient_gender"/></br>
                <input type="text" value="{{$data->timeslot_id}}" name="doctor_timeslot" id="doctor_timeslot"/></br>
                <input type="text" value="{{$data->fees}}" name="doctor_fees" id="doctor_fees"/></br>
                <input type="text" value="{{$data->app_fees}}" name="app_fees" id="app_fees"/></br>
            </div>
            <div class="d-flex my-2">
                <button type="submit" class="btn btn-primary w-100 mx-1"><i class="far fa-arrow-right"></i> Add Payment</button>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

@section("scripts")
<script>

$(document).ready(function(){
    let patient=@json($patient);

    $("#btn_book_someone_else").click(function(){
        $("#book_someone_else").show();
        $("#book_you").hide();
        
        // $("#patient_id").val(null);
        $("#patient_name").val($("#name").val());
        $("#patient_dob").val($("#dob").val());
        $("#patient_gender").val($("#gender").val());
        $("#name").on('change keyup paste', function() {
            $("#patient_name").val($("#name").val());
        });
        $("#dob").on('change keyup paste', function() {
            $("#patient_dob").val($("#dob").val());
        });
        $("#gender").change(function () {
            $("#patient_gender").val($("#gender").val());
        });
    });

    $("#btn_book_you").click(function(){
        $("#book_you").show();
        $("#book_someone_else").hide();

        // $("#patient_id").val(patient["id"]);
        // $("#patient_name").val(patient["fullname"]);
        // $("#patient_dob").val(patient["dob"]);
        // $("#patient_gender").val(patient["gender"]);
    });
});
</script>
@endsection