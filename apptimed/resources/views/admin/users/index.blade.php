@extends("layouts.main")

@section("main-body")
<h1 class="page-heading">Manage Users <span class="badge rounded-pill bg-warning text-white ms-3">{{sizeOf($data)}}</span></h1>

<!-- FILTER FORM -->
<form action="{{ route('users.index') }}" method="GET">
    <div class="row">
        <div class="col-md-4 col-8 d-flex search-box">
            <input type="text"  class="form-control" name="search" id="txt-search" value="{{$searchFilter}}" />
            <i class="fas fa-search search-icon"></i>
            <button type="submit" class="btn btn-primary">SEARCH</button>
        </div>
        <div class="col-md-2 col-4">
            <button type="button" class="btn btn-primary btn-primary-inverse mt-2 mt-md-0" id="btn-filter">
                <i class="fas fa-filter"></i> FILTER
            </button>
        </div>
        <div class="col-md-2 col-4">
            <a href="{{route('users.create')}}" style="" class="p-2 px-3 mt-2 mt-md-0 btn btn-success">
                <i class="fas fa-plus-square mx-1"></i> Add
            </a>
        </div>
        <div class="col-md-2 col-4">
            <select class="form-control" id="dlength-sel">
                <option value="20">20 Rows</option>
                <option value="50">50 Rows</option>
                <option value="100">100 Rows</option>
                
            </select>
        </div>
    </div>
<!-- END OF FILTER FORM -->
<div class="row" style="display:none" id="filter-panel">
    <div class="col-12">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Role</label>
                        <select class="form-control" name="role" id="role">
                            <option value="">All</option>
                            @foreach ($roles as $obj)
                                <option value="{{$obj->id}}" {{$roleFilter == $obj->id ? 'selected' : ''}}>{{$obj->name}}</option>
                            @endforeach
                        </select>
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
<!-- SITES TABLE -->
<div class="row mt-3">
    <div class="col-12">
        <div class="my-3 p-1 px-3 bg-body rounded shadow-sm ">
            <table class="table table-hover table-mobile" id="data-table">
                <thead>
                    <tr id="thead-data">
                        <th class="col-md">Name</th>
                        <th class="col-md">Role</th>
                        <th class="col-md">Email</th>
                        <th class="col-md">Status</th>
                        <th class="col-xs no-sort">View</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach($data as $user)
                    <tr>
                            <td>{{ $user->fname }} {{ $user->lname }}</td>
                            <td>{{ $user->role_name ? $user->role_name : '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                            <div">
                                @if($user->status == 1)
                                <a href="{{ route('users.show',[$user->id, 'sm_op'=>1]) }}"><span class="badge badge-pill badge-success">Active</span></a>
                                @else
                                <a href="{{ route('users.show',[$user->id, 'sm_op'=>1]) }}"><span class="badge badge-pill badge-danger">Inactive</span></a>
                                @endif
                            </div></td>
                            <td>
                                <a href="{{ route('users.show',$user->id) }}"  title="View" class="btn btn-sm btn-success btn-action"><i class="fas fa-eye mx-1"></i></a>
                            </td>
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