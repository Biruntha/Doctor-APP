@extends("layouts.main")

@section("main-body")
    
<h1 class="page-heading rounded">Edit Profile</h1>

<div class="row">
    <div class="col-md-12">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <!-- Nav tabs -->
            <ul class="nav nav-pills" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="px-3 text-dark nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-cont" type="button" role="tab" aria-controls="home" aria-selected="true">Basic Info</button>
                </li>
                @if($userType == "Doctor")
                <li class="nav-item" role="presentation">
                    <button class="px-3 text-dark nav-link" id="qualifications-tab" data-bs-toggle="tab" data-bs-target="#qualifications-cont" type="button" role="tab" aria-controls="profile" aria-selected="false">Qualifications</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="px-3 text-dark nav-link" id="specializations-tab" data-bs-toggle="tab" data-bs-target="#specializations-cont" type="button" role="tab" aria-controls="messages" aria-selected="false">Specializations</button>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="my-0 p-3 bg-body rounded shadow-sm">
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- =================== BASIC DETAILS ============================ -->
                <div class="tab-pane active" id="basic-cont" role="tabpanel" aria-labelledby="basic-cont">
                    <form action="{{ route('update-profile') }}" method="POST" enctype='multipart/form-data'>
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="my-3 p-3 bg-body rounded shadow-sm text-center">
                                    <h6 class="mb-4">Display Picture</h6>
                                    <div class="nav-item user-info mt-4 mb-4 text-center">
                                        @if($user->image == null or $user->image == "")
                                            <img class="border img-responsive" id="img-dp" src="{{asset('storage/UserImages/default-dp.png')}}"/>
                                        @else
                                            <img class="border img-responsive" id="img-dp" src="{{asset('storage/UserImages/'.$user->image)}}"/>
                                        @endif
                                    </div>
                                    <input type='file' name='profile_image' class="form-control"/>
                                    <button type="button" class="btn btn-light mt-2 m-auto" onclick="deleteDp()">DELETE</button>
                                    <input type="hidden" name="delete-dp" value="0" id="delete-dp"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="fname" class="form-label">First Name <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" value="{{ $user->fname }}" placeholder="First Name" type="text" name="fname" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="lname" class="form-label">Last Name <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" value="{{ $user->lname }}" placeholder="Last Name" type="text" name="lname" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="oname" class="form-label">Other Name</label>
                                    <input class="form-control mx-1" value="{{ $user->oname }}" placeholder="Other Name" type="text" name="oname" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" disabled value="{{ $user->email }}" placeholder="Email" type="text" name="email" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Primary Contact Number <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" value="{{ $user->contact }}" placeholder="Primary Contact Number" type="text" name="contact" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="contact_secondary" class="form-label">Secondary Contact Number</label>
                                    <input class="form-control mx-1" value="{{ $user->contact_secondary }}" placeholder="Secondary Contact Number" type="text" name="contact_secondary" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender @if($userType == "Patient")<strong style="color:red">*</strong>@endif</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">All</option>
                                        <option @if($user->gender == 'Male') selected @endif value="Male">Male</option>
                                        <option @if($user->gender == 'Female') selected @endif value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth @if($userType == "Patient")<strong style="color:red">*</strong>@endif</label>
                                    <input type="date" value="{{$user->dob}}" name="dob" id="dob" class="form-control" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Introductory Text</label>
                                    <textarea class="form-control mx-1" placeholder="Anything about you in few words" type="text" name="remarks">{{ $user->remarks }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="found_mode" class="form-label">How did you find us?</label>
                                    <select class="form-control" name="found_mode" id="found_mode">
                                        <option value="">All</option>
                                        <option @if($user->found_mode == 'Facebook/Instagram Ads') selected @endif value="Facebook/Instagram Ads">Facebook/Instagram Ads</option>
                                        <option @if($user->found_mode == 'Google Ads') selected @endif value="Google Ads">Google Ads</option>
                                        <option @if($user->found_mode == 'Heard from colleague') selected @endif value="Heard from colleague">Heard from colleague</option>
                                        <option @if($user->found_mode == 'Heard from University') selected @endif value="Heard from University">Heard from University</option>
                                        <option @if($user->found_mode == 'Other') selected @endif value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            @if($userType == "Patient")
                            <div class="col-12  col-md-6 d-none">
                                <div class="mb-3">
                                        <label for="latititude" class="form-label">Latititude</label>
                                        <input class="form-control mx-1" value="{{ $user->latititude }}" placeholder="Latititude" type="text" name="latititude" />
                                    </div>
                                </div>
                                <div class="col-12  col-md-6 d-none">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input class="form-control mx-1" value="{{ $user->longitude }}" placeholder="Longitude" type="text" name="longitude" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3 text-left">
                                        <label for="bank_branch" class="form-label">Bank and Branch name <strong style="color:red">*</strong></label>
                                        <input class="form-control mx-1" placeholder="Bank and Branch name" type="text" value="{{$user->bank_branch}}" name="bank_branch"/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3 text-left">
                                        <label for="bank_account_no" class="form-label">Bank Account No <strong style="color:red">*</strong></label>
                                        <input class="form-control mx-1" placeholder="Bank Account No" type="text" value="{{$user->bank_account_no}}" name="bank_account_no"/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3 text-left">
                                        <label for="bank_holder_name" class="form-label">Bank Account Holder Name <strong style="color:red">*</strong></label>
                                        <input class="form-control mx-1" placeholder="Bank Account Holder Name" type="text" value="{{$user->bank_holder_name}}" name="bank_holder_name"/>
                                    </div>
                                </div>
                            @endif

                            @if($userType == "Doctor")
                            <div class="col-12 col-md-6">
                                <div class="mb-3 text-left">
                                    <label for="title" class="form-label">Title <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" placeholder="Title" type="text" value="{{$user->title}}" name="title"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3 text-left">
                                    <label for="registration_id" class="form-label">Registration No <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" placeholder="Registration No" type="text" value="{{$user->registration_id}}" name="registration_id"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3 text-left">
                                    <label for="timeslot_duration" class="form-label">Average Consultation Time(Min) <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" placeholder="Consultation Time" type="number" value="{{$user->timeslot_duration}}" name="timeslot_duration"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3 text-left">
                                    <label for="fees" class="form-label">Consultation Fees <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" placeholder="Consultation Fees" type="number" value="{{$user->fees}}" name="fees"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3 text-left">
                                    <label for="bank_branch" class="form-label">Bank and Branch name <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" placeholder="Bank and Branch name" type="text" value="{{$user->bank_branch}}" name="bank_branch"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3 text-left">
                                    <label for="bank_account_no" class="form-label">Bank Account No <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" placeholder="Bank Account No" type="text" value="{{$user->bank_account_no}}" name="bank_account_no"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3 text-left">
                                    <label for="bank_holder_name" class="form-label">Bank Account Holder Name <strong style="color:red">*</strong></label>
                                    <input class="form-control mx-1" placeholder="Bank Account Holder Name" type="text" value="{{$user->bank_holder_name}}" name="bank_holder_name"/>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-12"><h4 class="mb-4 mt-5 fw-bold">Change Password</h4></div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Current Password</label>
                                    <input class="form-control mx-1" placeholder="Current Password" type="text" name="cpassword" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input class="form-control mx-1" placeholder="New Password" type="text" name="npassword" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Confirm Password</label>
                                    <input class="form-control mx-1" placeholder="Confirm Password" type="text" name="npassword2" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 offset-md-8 col-12 mt-4">
                            <button type="submit" class="btn p-3 btn-primary w-100 mx-1 mb-4">UPDATE</button>
                        </div>
                    </form>
                </div>
                
                <!-- =================== Qualifications, Specializations ============================ -->
                @if($userType == "Doctor")
                    <div class="tab-pane" id="qualifications-cont" role="tabpanel" aria-labelledby="qualifications-cont">
                        <div class="row">
                            <div class="col-9 d-flex"><h4 class="mb-4 mt-5 fw-bold">Qualification Details</h4></div>
                            <div class="col-3"><button type="button" class="btn btn-primary btn-primary-inverse float-end mb-4 mt-5" onclick="$('#qualification-modal').modal('show')"><i class="fas fa-plus mx-1"></i></button></div>
                            
                            @foreach($doctor_qualifications as $obj)
                                @php ($xlSize = 6)
                                @php ($mdSize = 12)
                                @php ($smSize = 12)
                                @include("tiles.qualification-tile")
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane" id="specializations-cont" role="tabpanel" aria-labelledby="specializations-cont">
                        <div class="row">
                            <div class="col-9 d-flex"><h4 class="mb-4 mt-5 fw-bold">Specializations</h4></div>
                            <div class="col-3"><button type="button" class="btn btn-primary btn-primary-inverse float-end mb-4 mt-5" onclick="$('#specialization-modal').modal('show')"><i class="fas fa-plus mx-1"></i></button></div>
                            
                            @foreach($doctor_specializations as $obj)
                                @php ($xlSize = 6)
                                @php ($mdSize = 12)
                                @php ($smSize = 12)
                                @include("tiles.specialization-tile")
                            @endforeach
                        </div>    
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<!-- MODALS ======================================================== -->
<!-- Add Qualification -->
<form method="get">
<div class="modal fade hide" id="qualification-modal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" >Add Qualification</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="qualification-modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert" id="qualification-alert" style="display:none">
                        
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="qualification" class="form-label">Qualification <strong style="color:red">*</strong></label>
                        <input class="form-control mx-1" placeholder="Qualification" type="text" name="Qualification" id="qualification"/>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input class="form-control mx-1" placeholder="Year" type="number" name="year" id="year"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light btn-lg px-4 rounded" data-bs-dismiss="modal">CLOSE</button>
            <button type="button" class="btn btn-primary btn-lg px-4 rounded text-white" onclick="addQualification()">SAVE</button>
        </div>
    </div>
  </div>
</div>
</form>
<!-- Edit Qualification -->
<form method="get" id="edit-qualification">
@csrf
<div class="modal fade hide" id="qualificationEditModal" tabindex="-1" aria-labelledby="qualificationEditModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="qualificationEditModalTitle">Edit Qualification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert" id="edit-qualification-alert" style="display:none">
                
                </div>
            </div>
            <input class="form-control mx-1" type="hidden" name="doc_qualification_id" id="doc_qualification_id"/>
            <div class="col-12">
                <div class="mb-3">
                    <label for="doc_qualification" class="form-label">Qualification <strong style="color:red">*</strong></label>
                    <input class="form-control mx-1" placeholder="Qualification" type="text" name="doc_qualification" id="doc_qualification"/>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label for="doc_qualification_year" class="form-label">Year</label>
                    <input class="form-control mx-1" placeholder="Year" type="number" name="doc_qualification_year" id="doc_qualification_year"/>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger p-2 px-3"  data-bs-dismiss="modal">CLOSE</button>
        <button type="button" onclick="updateQualification()" class="btn btn-info p-2 px-3 text-white">UPDATE</button>
      </div>
    </div>
  </div>
</div>
</form>


<!-- Add Specialization -->
<form method="get">
<div class="modal fade hide" id="specialization-modal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" >Add Specialization</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="specialization-modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert" id="specialization-alert" style="display:none">
                        
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Specialization <strong style="color:red">*</strong></label>
                        <select class="form-control" name="specialization" id="specialization">
                            <option value="">All</option>
                            @foreach ($specializations as $obj)
                                <option {{old('specialization') == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="experience_year" class="form-label">Experience Year</label>
                        <input class="form-control mx-1" placeholder="Experience Year" type="text" name="experience_year" id="experience_year"/>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="note" class="form-label">Notes</label>
                        <textarea class="form-control mx-1" placeholder="Notes" type="text" id="note" name="note"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light btn-lg px-4 rounded" data-bs-dismiss="modal">CLOSE</button>
            <button type="button" class="btn btn-primary btn-lg px-4 rounded text-white" onclick="addSpecialization()">SAVE</button>
        </div>
    </div>
  </div>
</div>
</form>
<!-- Edit Specialization -->
<form method="get" id="edit-specialization">
@csrf
<div class="modal fade hide" id="specializationEditModal" tabindex="-1" aria-labelledby="specializationEditModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="specializationEditModalTitle">Edit Specialization</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert" id="edit-specialization-alert" style="display:none">
                
                </div>
            </div>
            <input class="form-control mx-1" type="hidden" name="doc_specialization_id" id="doc_specialization_id"/>
            <div class="col-12">
                <div class="mb-3">
                    <label for="doc_specialization" class="form-label">Specialization <strong style="color:red">*</strong></label>
                    <select class="form-control" name="doc_specialization" id="doc_specialization">
                        <option value="">All</option>
                        @foreach ($specializations as $obj)
                            <option value="{{$obj->id}}">{{$obj->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label for="doc_experience_year" class="form-label">Experience Year</label>
                    <input class="form-control mx-1" placeholder="Experience Year" type="text" name="doc_experience_year" id="doc_experience_year"/>
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label for="doc_note" class="form-label">Notes</label>
                    <textarea class="form-control mx-1" placeholder="Notes" type="text" id="doc_note" name="doc_note"></textarea>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger p-2 px-3"  data-bs-dismiss="modal">CLOSE</button>
        <button type="button" onclick="updateSpecialization()" class="btn btn-info p-2 px-3 text-white">UPDATE</button>
      </div>
    </div>
  </div>
</div>
</form>
@endsection

@section("scripts")

<script>
function deleteDp()
{
    $("#delete-dp").val(1);
    $("#img-dp").attr("src", "{{asset('storage/UserImages/default-dp.png')}}");
}
</script>
@endsection
