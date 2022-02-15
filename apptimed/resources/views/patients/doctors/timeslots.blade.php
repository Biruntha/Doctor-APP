@extends("layouts.main")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Find a Timeslot
</h1>
@endsection

@section("main-body")
<div class="row mt-1">
    @foreach ($data as $obj)
        @php ($xlSize = 4)
        @php ($mdSize = 6)
        @php ($smSize = 12)
        @include("tiles.schedule-tile")
    @endforeach
</div>
@endsection

@section("scripts")
<script>
</script>
@endsection