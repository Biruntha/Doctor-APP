@extends("layouts.main-web")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Find a specialist doctor
</h1>
@endsection

@section("main-body")

<div class="row">
    <div class="col-md-6">
        <form class="filter-cont" action="{{ route('doctors') }}" method="GET" onsubmit="$('.page-loader').show()">
            <div class="row">
                <div class="col-12 d-flex search-box">
                    <input type="text"  class="form-control" name="searchFilter" value="{{$searchFilter}}" placeholder="Search"/>
                    <select class="form-control" name="specializationFilter" id="specializationFilter">
                        <option value="">Select Specialization</option>
                        @foreach ($specializations as $obj)
                            <option {{$specializationFilter == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mt-1">
    @foreach ($data as $obj)
        @php ($xlSize = 4)
        @php ($mdSize = 6)
        @php ($smSize = 12)
        @include("tiles.doctor-tile")
    @endforeach

    <div class="col-12">
        <div class="d-flex justify-content-center pagintation-cont">
            {!! $data->appends($_GET)->links() !!}
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
</script>
@endsection