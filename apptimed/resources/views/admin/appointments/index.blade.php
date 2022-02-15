@extends("layouts.main")

@section("main-body")
<form action="{{ route('appointments') }}" method="GET" onsubmit="$('.page-loader').show()">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-heading rounded">All Appointments <span class="badge rounded-pill bg-warning text-white ms-3">{{$appointments->total()}}</span></h1>
        </div>
        <div class="col-md-8">
            <div class="filter-cont">
                <div class="row">
                    <div class="col-md-6 col-6 d-flex search-box">
                        <input type="text"  class="form-control" name="searchFilter" value="{{$searchFilter}}" placeholder="Search"/>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="col-md-3 col-3">
                        <button type="button" class="btn btn-primary btn-primary-inverse" id="btn-filter">
                            <i class="fas fa-filter"></i> FILTER
                        </button>
                    </div>
                    <div class="col-md-3 col-3">
                        <!-- <a href="{{route('specializations.create')}}" class="btn btn-primary btn-primary-inverse float-end">
                            <i class="fas fa-plus mx-1"></i> <span class="d-none d-md-inline-block">ADD NEW</span>
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="display:none" id="filter-panel">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="doctor" class="form-label">Doctor</label>
                            <select class="form-control" name="doctor" id="doctor">
                                <option value="">All</option>
                                @foreach ($doctors as $obj)
                                    <option {{$doctorFilter == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="fdate" class="form-label">Date (From)</label>
                            <input type="date" value="{{$fdate}}" name="fdate" class="form-control" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="tdate" class="form-label">Date (To)</label>
                            <input type="date" value="{{$tdate}}" name="tdate" class="form-control" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="stime" class="form-label">Time (From)</label>
                            <input type="time" value="{{$stime}}" name="stime" class="form-control" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="etime" class="form-label">Time (To)</label>
                            <input type="time" value="{{$etime}}" name="etime" class="form-control" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="">All</option>
                                <option {{$statusFilter == 'Pending' ? 'selected' : ''}} value="Pending">Pending</option>
                                <option {{$statusFilter == 'Confirmed' ? 'selected' : ''}} value="Confirmed">Confirmed</option>
                                <option {{$statusFilter == 'Cancelled-by-Patient' ? 'selected' : ''}} value="Cancelled-by-Patient">Cancelled-by-Patient</option>
                                <option {{$statusFilter == 'Cancelled-by-Doctor' ? 'selected' : ''}} value="Cancelled-by-Doctor">Cancelled-by-Doctor</option>
                                <option {{$statusFilter == 'Cancelled-by-Admin' ? 'selected' : ''}} value="Cancelled-by-Admin">Cancelled-by-Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="row border-top-1 m-0 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-9 d-flex">
                            <button type="reset" class="btn btn-primary-inverse w-100 mx-1">RESET</button>
                            <button type="submit" class="btn btn-primary w-100 mx-1">SUBMIT</button>
                        </div>
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
                        <th class="col-md no-sort">Appointment No</th>
                        <th class="col-md no-sort">Date</th>
                        <th class="col-md no-sort">Start Time</th>
                        <th class="col-md no-sort">Doctor</th>
                        <th class="col-md no-sort">Patient</th>
                        <th class="col-md no-sort">Status</th>
                        <th class="col-md no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach ($appointments as $obj)
                        <tr>
                            <td>{{ $obj->appointment_number }}</td>
                            <td>{{ $obj->date }}</td>
                            <td>{{ $obj->start_time }}</td>
                            <td>{{ $obj->fullname }}</td>
                            <td>{{ $obj->patient_name }}</td>
                            <td>{{ $obj->status }}</td>
                            <td class="number d-flex">
                                @permission(['can-edit-appointment'])
                                    <a href="/manage/cancel-appointment/{{$obj->id}}" title="Cancel" class="btn btn-sm btn-danger btn-action"><i class="fas fa-window-close"></i></a>                                       
                                @endpermission()
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center pagintation-cont">
                {!! $appointments->appends($_GET)->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
</script>
@endsection