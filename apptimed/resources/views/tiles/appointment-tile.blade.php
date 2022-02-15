<div class="col-sm-{{$smSize != null ? $smSize : 12}} col-md-{{$mdSize != null ? $mdSize : 6}} col-xl-{{$xlSize != null ? $xlSize : 4}}">
    <a href="/patient/appointments/{{$obj->id}}">
        <div class="mb-4 p-2 bg-white rounded shadow-md vacancy-card">
            <div class="d-flex">
                <div class="details">
                    <h4>{{ $obj->date }} {{$obj->patient_name ? "(" .$obj->patient_name . ")" : ""}}</h4>
                    <p>{{ $obj->fullname }}</p>
                    <p>Appointment #{{ $obj->appointment_number }} {{ $obj->start_time }}</p>
                    <p>{{ $obj->status }}</p>
                </div>
            </div>
            <div class="d-flex more-details">
                <div class="detail-cont"><i class="fas fa-calendar-check"></i> </div>
                <div class="detail-cont"><i class="fas fa-user-clock"></i> </div>       
            </div>
            <div class="d-flex actions">
                <div class="action-cont bg-grad text-white"><i class="far fa-arrow-right"></i> View</div>       
            </div>
        </div>
    </a>
</div>