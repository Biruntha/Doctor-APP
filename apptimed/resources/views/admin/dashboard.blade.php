@extends("layouts.main")

@section("main-body")
<h1 class="page-heading">Welcome!</h1>

<!-- DASHBOARD TILES -->
<div class="row mb-5">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-3 col-6">
        <a href="{{url('/orders?status=All')}}">
          <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="icon-dash icon-primary p-1 px-2 mb-4 rounded"><i class="fas fa-receipt"></i></div>
            <h5 class="mb-1"></h5>
            <p class="m-0">Orders</p>
          </div>
        </a>
      </div>

      <div class="col-lg-2 col-md-2 col-sm-3 col-6">
        <a href="{{url('/orders?status=Approved')}}">
          <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="icon-dash icon-primary p-1 px-2 mb-4 rounded"><i class="far fa-clipboard-check"></i></div>
            <h5 class="mb-1"></h5>
            <p class="m-0">Approved</p>
          </div>
        </a>
      </div>

      <div class="col-lg-2 col-md-2 col-sm-3 col-6">
        <a href="{{url('/orders?status=Flagged')}}">
          <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="icon-dash icon-danger p-1 px-2 mb-4 rounded"><i class="fas fa-exclamation-circle"></i></div>
            <h5 class="mb-1"></h5>
            <p class="m-0">Flagged</p>
          </div>
        </a>
      </div>

      <div class="col-lg-2 col-md-2 col-sm-3 col-6">
        <a href="{{url('/orders?status=Tasks')}}">
          <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="icon-dash icon-success p-1 px-2 mb-4 rounded"><i class="fas fa-tasks"></i></div>
            <h5 class="mb-1"></h5>
            <p class="m-0">Tasks</p>
          </div>
        </a>
      </div>

      <div class="col-lg-2 col-md-2 col-sm-3 col-6">
        <a href="{{url('/orders?status=Written')}}">
          <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="icon-dash icon-warning p-1 px-2 mb-4 rounded"><i class="fas fa-file-invoice"></i></div>
            <h5 class="mb-1"></h5>
            <p class="m-0">Articles</p>
          </div>
        </a>
      </div>

      <div class="col-lg-2 col-md-2 col-sm-3 col-6">
        <a href="">
          <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="icon-dash icon-success p-1 px-2 mb-4 rounded"><i class="fas fa-users"></i></div>
            <h5 class="mb-1"></h5>
            <p class="m-0">Clients</p>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
<!-- END OF DASHBOARD TILES -->

@endsection