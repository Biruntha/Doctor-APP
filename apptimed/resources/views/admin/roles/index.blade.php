@extends("layouts.main")

@section("main-body")
<h1 class="page-heading">Manage Roles <span class="badge rounded-pill bg-warning text-white ms-3">{{sizeOf($data)}}</span></h1>

<!-- FILTER FORM -->
<form action="{{ route('roles.index') }}" method="GET">
    <div class="row">
        <div class="col-md-4 col-8 d-flex search-box">
            <input type="text"  class="form-control" name="search" id="txt-search" value="{{$searchFilter}}" />
            <i class="fas fa-search search-icon"></i>
            <button type="submit" class="btn btn-primary">SEARCH</button>
        </div>
        <div class="col-md-2 col-4">
            <select class="form-control" id="dlength-sel">
                <option value="20">20 Rows</option>
                <option value="50">50 Rows</option>
                <option value="100">100 Rows</option>
                
            </select>
        </div>
        <div class="col-md-2 col-4">
            <a href="{{route('roles.create')}}" class="btn btn-primary btn-primary-inverse">
                <i class="fas fa-plus-square mx-1"></i> MORE
            </a>
        </div>
    </div>
</form>
<!-- END OF FILTER FORM -->

<!-- SITES TABLE -->
<div class="row mt-3">
    <div class="col-12">
        <div class="my-3 p-1 px-3 bg-body rounded shadow-sm ">
            <table class="table table-hover table-mobile" id="data-table">
                <thead>
                    <tr id="thead-data">
                        <th class="col-sm">Name</th>
                        <th>Authorities</th>
                        <th class="col-xs no-sort">View</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach($data as $role)
                    <tr>
                            <td class="col-sm">{{ $role->name }}</td>
                            <td><div>
                                @foreach($role->permissions as $permission)
                                    @php
                                        $permission_name = ucfirst(str_replace('-',' ',$permission->name));
                                    @endphp
                                    @if(strpos($permission_name,'delete'))
                                        <span class="badge badge-pill badge-danger"> {{ $permission_name }}</span>
                                    @elseif(strpos($permission_name,'edit'))
                                        <span class="badge badge-pill badge-secondary"> {{ $permission_name }}</span>
                                    @elseif(strpos($permission_name,'add'))
                                        <span class="badge badge-pill badge-success"> {{ $permission_name }}</span>
                                    @else
                                        <span class="badge badge-pill badge-primary"> {{ $permission_name }}</span>
                                    @endif


                                @endforeach
                            </div>
                            </td>
                            <td>
                                <a href="{{ route('roles.show',$role->id) }}"  title="View" class="btn btn-sm btn-success btn-action"><i class="fas fa-eye mx-1"></i></a>
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