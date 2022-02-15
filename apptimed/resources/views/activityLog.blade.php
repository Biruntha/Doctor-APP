@extends("layouts.main")

@section("main-body")
<h1 class="page-heading">Activity Log <span class="badge rounded-pill bg-warning text-white ms-3"></span></h1>

<!-- FILTER FORM -->
<form action="{{ route('activity-logs.index') }}" method="GET">
    <div class="row">
        <div class="col-md-4 col-8 d-flex search-box">
            <input type="text"  class="form-control" name="search" id="txt-search" value="{{$search}}"/>
            <i class="fas fa-search search-icon"></i>
            <button type="submit" class="btn btn-primary">SEARCH</button>
        </div>

        <div class="col-md-2 col-4">
            <button type="button" class="btn btn-primary btn-primary-inverse" id="btn-filter">
                <i class="fas fa-filter"></i> FILTER
            </button>
        </div>
        <div class="col-md-2 col-4">
            <select class="form-control" id="dlength-sel">
                <option value="20">20 Rows</option>
                <option value="50">50 Rows</option>
                <option value="100">100 Rows</option>
                
            </select>
        </div>
    </div>

    <div class="row" style="display:none" id="filter-panel">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">

                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="fields" class="form-label">Started Date (From)</label>
                            <input type="date" value="{{$sDateFrom}}" name="fsdate" class="form-control" />
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="fields" class="form-label">Started Date (To)</label>
                            <input type="date" value="{{$sDateTo}}" name="tsdate" class="form-control" />
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
<!-- END OF FILTER FORM -->

<!-- SITES TABLE -->
<div class="row mt-3">
    <div class="col-12">
        <div class="my-3 p-1 px-3 bg-body rounded shadow-sm ">
            <table class="table table-hover" id="data-table">
                <thead>
                    <tr id="thead-data">
                        <th>Activity</th>
                        <th>User</th>
                        <th>Order</th>
                        <th>Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach($data as $obj)
                        <tr id="thead-data">
                            <td class="">{{$obj->activity_description}}</td>
                            <td class="">{{$obj->activityUser ? $obj->activityUser->fname. " " .$obj->activityUser->lname : "-" }}</td>
                            <td class="">{{$obj->order ? "#".$obj->order->id : "-" }}</td>
                            <td class="">{{$obj->activity_type}}</td>
                            <td class="">@displayDate($obj->created_at)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- END OF SITES -->
@endsection

@section("scripts")
<script>
    $(document).ready(function(){
        buildDataTable(false);

    });
</script>
@endsection
