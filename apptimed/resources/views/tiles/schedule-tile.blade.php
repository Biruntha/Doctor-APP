<div class="col-sm-{{$smSize != null ? $smSize : 12}} col-md-{{$mdSize != null ? $mdSize : 6}} col-xl-{{$xlSize != null ? $xlSize : 4}}">
    <a href="/reserve/{{$obj->timeslot_code}}">
        <div class="mb-4 p-2 bg-white rounded shadow-md vacancy-card">
            <div class="d-flex">
                <div class="details">
                    <h4>{{ $obj->date == $currentDate ? 'Today' : ($obj->date == $tomorrowDate ? 'Tomorrow' : $obj->date)}}</h4>
                    <p>{{ $obj->time_from }}</p>
                </div>
            </div>
            <div class="d-flex more-details">
                <div class="detail-cont"><i class="fas fa-calendar-check"></i> Appointment #{{ $obj->appointment_count ? $obj->appointment_count + 1 : 1 }}</div>
                <div class="detail-cont"><i class="fas fa-user-clock"></i> Est. By {{$obj->next_available_time ? $obj->next_available_time : $obj->time_from}}</div>       
            </div>
            <div class="d-flex actions">
                <div class="action-cont bg-grad text-white"><i class="far fa-arrow-right"></i> Choose</div>       
            </div>
        </div>
    </a>
</div>