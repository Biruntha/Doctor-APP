@extends("layouts.main")

@section("main-body")

<div class="row">
    <div class="col-md-4">
        <h1 class="page-heading rounded">Doctor's Monthly Time Slots <span class="badge rounded-pill bg-warning text-white ms-3">0</span></h1>
    </div>
    <div class="col-md-8">
        <div class="filter-cont">
            <div class="row">
                <div class="col-md-6 col-6 d-flex">
                    <button onclick="removeTimeslotModal()" class="btn btn-danger btn-danger-inverse float-end">
                        <i class="fas fa-trash mx-1"></i> <span class="d-none d-md-inline-block">Remove Timslots</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    
<div class="row mt-1">
    <div class="col-12">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex">
                <a class="btn btn-success me-2" href="/doctor/calender?month={{$preMonth}}&year={{$preYear}}">Prev</a>
                <input readonly class="form-control" type="month" value="{{date('Y-m', strtotime($currentYear.'-'.$currentMonth.'-1'))}}"/>
                <a class="btn btn-success ms-2" href="/doctor/calender?month={{$nextMonth}}&year={{$nextYear}}">Next</a>
            </div>
            <div>
                @foreach($dayLabels as $index=>$label)
                    <div  style="width:13%" class="d-inline-block">{{$label}}</div>
                @endforeach
            </div>
            <div>
            @for( $i=1; $i <= $firstDayOfTheWeek; $i++ )
                <div class="mb-1 my-md-3 p-3 bg-body rounded shadow-sm d-inline-block" style="width:13%">
                    <p class="m-0">OO</p>
                </div>
            @endfor
            @for( $i = 1 + $firstDayOfTheWeek; $i <= $daysInMonth + $firstDayOfTheWeek; $i++ )
                <div class="mb-1 my-md-3 p-3 bg-body rounded shadow-sm d-inline-block" style="width:13%">
                    <a href="{{url('/doctor/timeslots?startDate='.$currentDate. '-' . substr('0'. ($i - $firstDayOfTheWeek), -2) .'&endDate='.$currentDate. '-' . substr('0'. ($i - $firstDayOfTheWeek), -2))}}">
                        <span>
                            <i class="fas fa-file-alt"></i>
                            {{isset($day_appointments[$currentYear  . '-' . substr("0". ($currentMonth), -2) . '-' . substr("0". ($i - $firstDayOfTheWeek), -2)]) ? $day_appointments[$currentYear  . '-' . substr("0". ($currentMonth), -2) . '-' . substr("0". ($i - $firstDayOfTheWeek), -2)] : '0'}}
                            <i class="fas fa-clock"></i> 
                            {{isset($day_total_hours[$currentYear  . '-' . substr("0". ($currentMonth), -2) . '-' . substr("0". ($i - $firstDayOfTheWeek), -2)]) ? $day_total_hours[$currentYear  . '-' . substr("0". ($currentMonth), -2) . '-' . substr("0". ($i - $firstDayOfTheWeek), -2)] : '0H'}}
                        </span>   
                        <p class="m-0">{{$i - $firstDayOfTheWeek}}</p>
                    </a>
                    <button onclick="showTimeslotModal({{ $currentYear }}, {{ $currentMonth }}, {{$i - $firstDayOfTheWeek}})" class="btn btn-info float-end">
                        <i class="fas fa-plus mx-1"></i>
                    </button>
                    
                </div>
            @endfor
            </div>
            <div class="row mt-1">
                <div class="col-12">
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        Total Allocated Time - {{$month_total_hours}}
                        Total Booked Time - 0h
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection