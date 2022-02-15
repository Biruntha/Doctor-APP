@extends("layouts.main-web")

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Error Page
</h1>
@endsection

@section("main-body")
<div class="app-container p-2">
<div class="row">
    <div class="col-md-8 col-sm-12 offset-md-2">
        <div class="bg-white shadow-md rounded p-3 p-md-5 mb-5 mt-4" style="align-items: center;position:relative">
        <div class="row">
            <div class="col-12">
                <div class="mb-3 text-center">
                <label class="form-label"><h1>The page you are looking for could not be found</h1></label>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
@endsection