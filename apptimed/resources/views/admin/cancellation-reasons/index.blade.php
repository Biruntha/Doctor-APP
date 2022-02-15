@extends("layouts.main")

@section("main-body")

<form action="{{ route('cancellation-reasons.index') }}" method="GET" onsubmit="$('.page-loader').show()">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-heading rounded">Cancellation Reasons <span class="badge rounded-pill bg-warning text-white ms-3">{{$data->total()}}</span></h1>
        </div>
        <div class="col-md-8">
            <div class="filter-cont">
                <div class="row">
                    <div class="col-md-6 col-6 d-flex search-box">
                        <input type="text"  class="form-control" name="searchFilter" value="{{$searchFilter}}" placeholder="Search"/>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="col-md-3 col-3">
                    </div>
                    <div class="col-md-3 col-3">
                        <a href="{{route('cancellation-reasons.create')}}" class="btn btn-primary btn-primary-inverse float-end">
                            <i class="fas fa-plus mx-1"></i> <span class="d-none d-md-inline-block">ADD NEW</span>
                        </a>
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
                        <th class="col-md no-sort">Description</th>
                        <th class="col-md no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $obj->name }}</td>
                            <td>{{ $obj->description }}</td>
                            <td class="number d-flex">
                                @permission(['can-edit-cancellationreason'])
                                    <a href="{{ route('cancellation-reasons.edit',$obj->id) }}" title="Edit" class="btn btn-sm btn-primary btn-action"><i class="fas fa-edit"></i></a>                                       
                                @endpermission()
                                @permission(['can-delete-cancellationreason'])                                    
                                    <form action="{{ route('cancellation-reasons.destroy',$obj->id) }}" title="Delete" class="confirm-action" method="POST">
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