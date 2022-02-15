<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Apptimus Tech">
    <meta name="generator" content="Hugo 0.83.1">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Placements.lk | Signin/Signup</title>

    <link rel="icon" href="/assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css?v=5" rel="stylesheet">
    <link href="/assets/css/countrySelect.css" rel="stylesheet">
    <link href="/assets/css/file-upload.css" rel="stylesheet">
  </head>
  <body>
        <div class="page-loader" id="page-loader" >
          <img src="/assets/images/loader.gif" class="m-auto" />
        </div>
<nav class="navbar navbar-expand-lg fixed-top shadow-sm" aria-label="Main navigation">
  <div class="container-fluid-2 w-100">
    <a class="navbar-brand bg-white ms-md-5" href="{{route('dashboard')}}">
        <img src="/assets/images/logo.png" class=""/>
    </a>

    <div class="header-icon-cont float-end">
      <a class="btn header-icon ms-2" href="/">
        Home
      </a>
      <a href="/doctors" class="btn header-icon ms-2">
        Doctors
      </a>
      <a href="/career-fairs" class="btn header-icon ms-2">
        Career Fairs
      </a>
      <a href="/companies" class="btn header-icon ms-2">
        Companies
      </a>
      @if(Auth::check())
      <a class="btn bg-grad text-white header-icon ms-2 p-2 px-3" href="/dashboard">
       <span class="fas fa-th-large fw-normal"></span> Dashboard
      </a>
      @else
      <a class="btn btn-danger text-white header-icon ms-2 p-2 px-3" href="/login">
       <span class="far fa-sign-in-alt"></span> Login/Register
      </a>
      @endif
    </div>
  </div>
</nav>
@yield("title")
@yield("banner")

<main class="container position-relative web">
  @if (Session::has('alert-unauthorized-access-class'))
      <div class="alert alert-danger fade show mt-2 common-alert">{!! Session::get('alert-unauthorized-access-class') !!}</div>
      @php
          Session::forget('alert-unauthorized-access-class')
      @endphp
  @endif

  @if ($message = Session::get('message'))
      <div class="mt-2 alert alert-success common-alert">
          <b>{{ $message }}</b>
      </div>
  @endif
  @if ($error = Session::get('error'))
      <div class="mt-2 alert alert-danger common-alert">
          <b>{{ $error }}</b>
      </div>
  @endif

  @yield("main-body")
</main>
<div class="bg-overlay bg-grad d-none"></div>


<div class="mobile-footer bg-white shadow d-none">
  <div class="p-2 px-4">
    <a class="btn header-icon" href="/vacancies">
      <i class="fas fa-th-large"></i>
    </a>
  </div>
  <div class="p-2 px-4">
    @if(Auth::check())
      <a class="btn float-end header-icon" href="">
        <i class="fas fa-bell m-0"></i>
        @if(isset($notificationCount))
          <span class="notification-cont">
            {{ $notificationCount }}
          </span>
        @endif
      </a>
    @else
      <a class="btn header-icon" href="/companies">
        <i class="fas fa-building"></i>
      </a>
    @endif
  </div>
  <div class="p-2 px-4">
    @if(Auth::check() and Auth::user()->role == null)
      <a class="btn header-icon" href="">
        <i class="fas fa-shopping-cart me-1"></i>
        @if(isset($cartCount))
          <span class="cart-count notification-cont">
            {{ $cartCount }}
          </span>
        @endif
      </a>
    @else
      <a class="btn header-icon" href="{{url('/login')}}">
        <i class="fas fa-sign-in-alt"></i>
      </a>
    @endif
  </div>
</div>      
      

<!-- TOASTS ======================================================== -->
<div class="position-fixed end-0 p-3" style="z-index: 1020;top:70px">
  <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header icon-success text-center">

      <strong class="me-auto"><i class="far fa-smile-wink mx-2" style="font-size:20px"></i> Success!</strong>
      <small></small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Successfully added
    </div>
  </div>
</div>

<div class="position-fixed end-0 p-3" style="z-index: 1020;top:70px">
  <div id="failedToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header icon-danger text-center">

      <strong class="me-auto"><i class="far fa-frown mx-2"  style="font-size:20px"></i> Sorry!</strong>
      <small></small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
    </div>
  </div>
</div>
<!-- END OF TOASTS ======================================================== -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ URL::asset('assets/scripts/scripts.js?v=4') }}"></script>

<!-- Custom scripts for this template -->
<script src="/assets/js/request_handler.js"></script>
<script src="/assets/js/scripts.js?v=5"></script>

@yield('scripts')


  </body>
</html>
