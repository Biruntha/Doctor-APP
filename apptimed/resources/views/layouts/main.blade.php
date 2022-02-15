<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Apptimus Tech">
    <meta name="generator" content="Hugo 0.83.1">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Placements.lk</title>

    <link rel="icon" href="/assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css?v=5" rel="stylesheet">
    <link href="/assets/css/countrySelect.css" rel="stylesheet">
    <link href="/assets/css/file-upload.css" rel="stylesheet">
    <link href="/assets/libs/apex-charts/apexcharts.css?v=5" rel="stylesheet">
  </head>
  <body>
        <div class="page-loader" id="page-loader" >
          <img src="/assets/images/loader.gif" class="m-auto" />
        </div>
<nav class="navbar navbar-expand-lg fixed-top shadow-sm" aria-label="Main navigation">
  <div class="container-fluid-2 w-100">
    <a class="navbar-brand" href="{{route('dashboard')}}">
        <img src="/assets/images/logo-white.png" class="d-none d-md-inline-block"/>
        <img src="/assets/images/logo.png" class="d-inline-block d-md-none"/>
    </a>

    <button class="navbar-toggler p-0 border-0 float-end mt-2 me-2" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </button>
    <div class="header-icon-cont float-end">
      <a href="/" class="btn btn-warning text-white header-icon p-2 px-3 me-3">
        <span class="far fa-home"></span> Home
      </a>
      <a class="btn header-icon" href="">
        <i class="fas fa-expand me-1"></i>
      </a>
      <a class="btn float-end header-icon" href="">
       <i class="far fa-bell"></i>
        @if(isset($notificationCount))
          <span class="notification-cont">
            {{ $notificationCount }}
          </span>
        @endif
      </a>
      <a href="{{ route('edit-profile') }}" class="btn header-icon">
        <i class="far fa-user"></i>
      </a>
    </div>
  </div>
</nav>
@yield("title")

<div class="sidebar-default mini-scrollbar shadow-sm" id="navbarsExampleDefault">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

    @if(Auth::check())
    <li class="nav-item user-info mt-4 mb-4 text-center">
      <img src="{{empty(Auth::user()->image) ? '/storage/UserImages/default-dp.png' : '/storage/UserImages/'.Auth::user()->image}}" />
      <strong class="mt-2 d-block">Hello, {{Auth::user()->fname}}</strong>
      <p class="">{{Auth::user()->email}}</p>
    </li>
    @endif

    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{route('dashboard')}}"><i class="fas fa-th"></i> Dashboard</a></li>

    <!-- *************************************************************** -->
    <!-- PUBLIC MENU =================================================== -->
    @if(!Auth::check())
        <!-- Vacancy -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('doctors') }}"><i class="fab fa-searchengin"></i> Doctors</a></li>

        <!-- Companies -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('companies-jobseeker') }}"><i class="far fa-building"></i> Companies</a></li>

        
    @endif


    <!-- *************************************************************** -->
    <!-- PATIENT MENU =============================================== -->
    @if(Auth::check() and Auth::user()->type == 'Patient')

        <!-- Doctor -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('doctors') }}"><i class="fab fa-searchengin"></i> Doctors</a></li>
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('my-appointments') }}"><i class="fab fa-searchengin"></i> My Appointments</a></li>
    @endif


    <!-- *************************************************************** -->
    <!-- DOCTOR MENU ================================================== -->
    @if(Auth::check() and Auth::user()->type == 'Doctor')

        <!-- Timeslots -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('my-calender') }}"><i class="fas fa-calendar-plus"></i> My Calender</a></li>
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('doctor-timeslots') }}"><i class="fas fa-clock"></i> Timeslots</a></li>
    @endif
    
    <!-- *************************************************************** -->
    <!-- ADMIN MENU ==================================================== -->
    @if(Auth::check())

        <!-- specialization -->
        @permission(['can-view-specialization'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('specializations.index') }}"><i class="fas fa-user-graduate"></i> Specializations</a></li>
        @endpermission()

        <!-- problemtype -->
        @permission(['can-view-problemtype'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('problem-types.index') }}"><i class="fas fa-heart-broken"></i> Problem Types</a></li>
        @endpermission()

        <!-- problem -->
        @permission(['can-view-problem'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('problems.index') }}"><i class="fas fa-disease"></i> Problems</a></li>
        @endpermission()

        <!-- cancellationreason -->
        @permission(['can-view-cancellationreason'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('cancellation-reasons.index') }}"><i class="fas fa-phone-slash"></i> Cancellation Reasons</a></li>
        @endpermission()

        <!-- transferreason -->
        @permission(['can-view-transferreason'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('transfer-reasons.index') }}"><i class="fas fa-exchange-alt"></i> Transfer Reasons</a></li>
        @endpermission()

         <!-- appointments -->
        @permission(['can-view-appointment'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('appointments') }}"><i class="fas fa-calendar-check"></i> Appointments</a></li>
        @endpermission()

        <!-- Others -->
        @if(Auth::user()->role == 1)
        <li class="nav-item">
          <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i> Others</a>
          <ul class="dropdown-menu" aria-labelledby="dropdown02">      
            
            <!-- USERS MENU ================================================== -->
            @permission(['can-view-user'])
              <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{route('users.index')}}"><i class="fas fa-user-circle"></i> Users</a></li>
            @endpermission()

            <!-- ROLES MENU ================================================== -->
            @permission(['can-view-role'])
              <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{route('roles.index')}}"><i class="fas fa-users-cog"></i> Roles</a></li>
            @endpermission()

          </ul>
        </li>
        @endif
    @endif

    
    <!-- *************************************************************** -->
    <!-- COMMON AUTHENTICATED USERS MENU =============================== -->
    @if(Auth::check())
    
    <!-- LOGOUT MENU ================================================== -->
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="#" onclick="$('#frm-logout').submit()"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

    <form method="POST" action="{{ route('logout') }}" id="frm-logout">
      @csrf
    </form>
    @endif

    </ul>
</div>

<main class="container position-relative">
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

<!-- MODALS ======================================================== -->
<form method="get" id="add-timeslots">
@csrf
<div class="modal fade hide" id="timeslotAddModal" tabindex="-1" aria-labelledby="timeslotAddModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="timeslotAddModalTitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="alert alert-danger" role="alert" id="timeslot-alert" style="display:none">
              
            </div>
          </div>
          <div class="col-12">
            <div class="mb-3">
              <label for="doctor_date" class="form-label">Date <strong style="color:red">*</strong></label>
              <input type="date" value="{{old('doctor_date')}}" disabled name="doctor_date" id="doctor_date" class="form-control" />
              <label for="doctor_time_from" class="form-label">Start Time <strong style="color:red">*</strong></label>
              <input type="time" value="{{old('doctor_time_from')}}" name="doctor_time_from" id="doctor_time_from" class="form-control" />
              <label for="doctor_time_to" class="form-label">End Time <strong style="color:red">*</strong></label>
              <input type="time" value="{{old('doctor_time_to')}}" name="doctor_time_to" id="doctor_time_to" class="form-control" />
              <label for="no_of_appointments" class="form-label">Max no of Appointments</label>
              <input class="form-control mx-1" value="{{old('no_of_appointments')}}" placeholder="Max no of Appointments" type="number" name="no_of_appointments" />
              <input type="checkbox" name="recurring" id="recurring"/>
              <label for="recurring" class="form-label mt-4">Recurring</label>            
            </div>
            <div class="mb-3"  id="recurring_details" style="display:none">
              <label for="doctor_end_date" class="form-label">Ends on</label>
              <input type="date" value="{{old('doctor_end_date')}}" name="doctor_end_date" id="doctor_end_date" class="form-control" />
              <label for="week_days" class="form-label">Repeat on</label>
              <select style="width: 50%" id="week_days" class="modal-select2" name="week_days[]" multiple="multiple">
                <option selected value="Sunday">Sunday</option>
                <option selected value="Monday">Monday</option>
                <option selected value="Tuesday">Tuesday</option>
                <option selected value="Wednesday">Wednesday</option>
                <option selected value="Thursday">Thursday</option>
                <option selected value="Friday">Friday</option>
                <option selected value="Saturday">Saturday</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger p-2 px-3"  data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="saveTimeSlot()" class="btn btn-info p-2 px-3 text-white">Confirm</button>
      </div>
    </div>
  </div>
</div>
</form>

<form method="get" id="edit-timeslots">
@csrf
<div class="modal fade hide" id="timeslotEditModal" tabindex="-1" aria-labelledby="timeslotEditModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="timeslotEditModalTitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="alert alert-danger" role="alert" id="timeslot-edit-alert" style="display:none">
              
            </div>
          </div>
          <div class="col-12">
            <div class="mb-3">
              <input type="hidden" name="timeslot_id" id="timeslot_id" class="form-control" />
              <label for="timeslot_date" class="form-label">Date <strong style="color:red">*</strong></label>
              <input type="date" value="{{old('timeslot_date')}}" disabled name="timeslot_date" id="timeslot_date" class="form-control" />
              <label for="timeslot_start_time" class="form-label">Start Time <strong style="color:red">*</strong></label>
              <input type="time" value="{{old('timeslot_start_time')}}" name="timeslot_start_time" id="timeslot_start_time" class="form-control" />
              <label for="timeslot_end_time" class="form-label">End Time <strong style="color:red">*</strong></label>
              <input type="time" value="{{old('timeslot_end_time')}}" name="timeslot_end_time" id="timeslot_end_time" class="form-control" />
              <label for="timeslot_appointments" class="form-label">Max no of Appointments</label>
              <input class="form-control mx-1" value="{{old('timeslot_appointments')}}" placeholder="Max no of Appointments" type="number" id="timeslot_appointments" name="timeslot_appointments" />       
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger p-2 px-3"  data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="updateTimeSlot()" class="btn btn-info p-2 px-3 text-white">Update</button>
      </div>
    </div>
  </div>
</div>
</form>

<form method="get" id="delete-timeslots">
@csrf
<div class="modal fade hide" id="timeslotDeleteModal" tabindex="-1" aria-labelledby="timeslotDeleteModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="timeslotDeleteModalTitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="alert alert-danger" role="alert" id="timeslot-delete-alert" style="display:none">
              
            </div>
          </div>
          <div class="col-12">
            <div class="mb-3">
              <label for="delete_start_date" class="form-label">Start Date <strong style="color:red">*</strong></label>
              <input type="date" value="{{old('delete_start_date')}}" name="delete_start_date" id="delete_start_date" class="form-control" />
              <label for="delete_end_date" class="form-label">Ends Date <strong style="color:red">*</strong></label>
              <input type="date" value="{{old('delete_end_date')}}" name="delete_end_date" id="delete_end_date" class="form-control" />        
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger p-2 px-3"  data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="deleteTimeSlot()" class="btn btn-info p-2 px-3 text-white">Delete</button>
      </div>
    </div>
  </div>
</div>
</form>
@yield("modal")
<!-- END OF MODALS ======================================================== -->

<div class="mobile-footer bg-white shadow d-none">
  <div class="p-2 px-4">
    <a class="btn header-icon" href="{{route('dashboard')}}">
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
      <a class="btn header-icon" href="{{ route('companies-jobseeker') }}">
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
      

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="{{ URL::asset('assets/scripts/scripts.js?v=4') }}"></script>
<script src="{{ URL::asset('assets/scripts/sweetalert.min.js') }}"></script>

<!-- Custom scripts for this template -->
<script src="/assets/js/request_handler.js"></script>
<script src="/assets/js/scripts.js?v=5"></script>
<script src="/assets/libs/apex-charts/apexcharts.min.js?v=4"></script>


<script>
(function () {
  'use strict'

  document.querySelector('#navbarSideCollapse').addEventListener('click', function () {
    document.querySelector('.sidebar-default').classList.toggle('open')
  })
})();


$(".modal-select2").select2({
    dropdownParent: $("#timeslotAddModal")
  });

if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}
</script>

@yield('scripts')


  </body>
</html>
