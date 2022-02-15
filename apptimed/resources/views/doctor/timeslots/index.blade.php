@extends("layouts.main")

@section("main-body")

<form action="{{ route('doctor-timeslots') }}" method="GET" onsubmit="$('.page-loader').show()">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-heading rounded">Doctor's Timeslots <span class="badge rounded-pill bg-warning text-white ms-3">{{$data->total()}}</span></h1>
        </div>
    </div>
    <div class="row" id="filter-panel">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date (From)</label>
                            <input type="date" value="{{$startDateFilter}}" name="startDate" class="form-control" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="endDate" class="form-label">End Date (To)</label>
                            <input type="date" value="{{$endDateFilter}}" name="endDate" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row border-top-1 pt-4 mt-2">
                    <div class="col-12 col-md-3 offset-md-9 d-flex">
                        <button type="reset" class="btn btn-primary-inverse w-100 mx-1">RESET</button>
                        <button type="submit" class="btn btn-primary w-100 mx-1">SUBMIT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


    
<div class="row mt-1">
    <div class="col-12">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <table class="table w-100 table-mobile" id="data-table">
                <thead id="thead-data">
                    <tr>
                        <th class="col-md no-sort">Date</th>
                        <th class="col-md no-sort">Start Time</th>
                        <th class="col-md no-sort">End Time</th>
                        <th class="col-md no-sort">No of Appointments</th>
                        <th class="col-md no-sort">Amount</th>
                        <th class="col-md no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $obj->date }}</td>
                            <td>{{ $obj->time_from }}</td>
                            <td>{{ $obj->time_to }}</td>
                            <td>{{ $obj->appointment_count ? $obj->appointment_count : 0 }}</td>
                            <td>Rs {{ $obj->amount ? $obj->amount : 0 }}</td>
                            <td class="number d-flex">
                                <button onclick="editTimeslotModal('{{ $obj->id }}', '{{ $obj->date }}', '{{ $obj->time_from }}', '{{$obj->time_to}}', '{{ $obj->max_appointments }}')" title="Edit" class="btn btn-sm btn-primary btn-action"><i class="fas fa-edit"></i></button>                                       
                                <form action="{{ route('delete-timeslot', $obj->id) }}" title="Delete" class="confirm-action" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mx-1 btn-action"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center pagintation-cont">
                {!! $data->appends($_GET)->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection