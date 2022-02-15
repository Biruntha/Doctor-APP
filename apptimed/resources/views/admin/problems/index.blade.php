@extends("layouts.main")

@section("main-body")

<form action="{{ route('problems.index') }}" method="GET" onsubmit="$('.page-loader').show()">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-heading rounded">Problems <span class="badge rounded-pill bg-warning text-white ms-3">{{$data->total()}}</span></h1>
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
                        <a href="{{route('problems.create')}}" class="btn btn-primary btn-primary-inverse float-end">
                            <i class="fas fa-plus mx-1"></i> <span class="d-none d-md-inline-block">ADD NEW</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="display:none" id="filter-panel">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="problem_type" class="form-label">Problem Type</label>
                            <select class="form-control" name="problem_type" id="problem_type">
                                <option value="">All</option>
                                @foreach ($problemTypes as $obj)
                                    <option {{$problemTypeFilter == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
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
                        <th class="col-md no-sort">Name</th>
                        <th class="col-md no-sort">Problem Type</th>
                        <th class="col-md no-sort">Description</th>
                        <th class="col-md no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $obj->name }}</td>
                            <td>{{ $obj->problem_type }}</td>
                            <td>{{ $obj->description }}</td>
                            <td class="number d-flex">
                                @permission(['can-edit-problem'])
                                    <a href="{{ route('problems.edit',$obj->id) }}" title="Edit" class="btn btn-sm btn-primary btn-action"><i class="fas fa-edit"></i></a>                                       
                                @endpermission()
                                @permission(['can-delete-problem'])                                    
                                    <form action="{{ route('problems.destroy',$obj->id) }}" title="Delete" class="confirm-action" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mx-1 btn-action"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endpermission()
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