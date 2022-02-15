@extends("layouts.main")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    My Appointments
</h1>
@endsection

@section("main-body")   

<div class="row mt-1">
    @foreach ($appointments as $obj)
        @php ($xlSize = 4)
        @php ($mdSize = 6)
        @php ($smSize = 12)
        @include("tiles.appointment-tile")
    @endforeach

    <div class="col-12">
        <div class="d-flex justify-content-center pagintation-cont">
            {!! $appointments->appends($_GET)->links() !!}
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
</script>
@endsection