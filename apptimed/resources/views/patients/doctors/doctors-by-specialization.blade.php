@extends("layouts.main-web")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Find a specialist doctor
</h1>
@endsection

@section("main-body")


    

<div class="row mt-1">
    @foreach ($specializations as $specialization)
        @if(array_key_exists($specialization->id, $specializationsDoctorMap))
            <h1 class="p-2 p-md-3 bg-grad mx-1 text-center">
                {{$specialization->name}}
            </h1>
            @foreach ($specializationsDoctorMap[$specialization->id] as $doc_id)
                @php ($obj = $doctorsMap[$doc_id])
                @php ($xlSize = 4)
                @php ($mdSize = 6)
                @php ($smSize = 12)
                @include("tiles.doctor-tile")
            @endforeach
        @endif
    @endforeach


    <div class="col-12">
        <div class="d-flex justify-content-center pagintation-cont">
            
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
</script>
@endsection