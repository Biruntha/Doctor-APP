@extends("layouts.main")

@section("main-body")
    <h1>Create User</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger fade show">{{ $error }}</div>
        @endforeach
    @endif

    <form action="{{ route('users.store') }}" method="POST" files="true" enctype="multipart/form-data">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    @csrf
                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="fname" class="form-label">First Name</label>
                            <input class="form-control mx-1" placeholder="First Name" type="text" name="fname" value="{{old('fname')}}" />
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="lname" class="form-label">Last Name</label>
                            <input class="form-control mx-1" placeholder="First Name" type="text" name="lname" value="{{old('lname')}}" />
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" name="role" id="role">
                                @foreach ($roles as $obj)
                                    <option value="{{$obj->id}}" {{old('role') == $obj->id ? 'selected' : ''}}>{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input class="form-control mx-1" placeholder="Email" type="text" name="email" value="{{old('email')}}" />
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control mx-1" type='password' placeholder="Password" type="text" name="password" value="{{old('password')}}" />
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="lname" class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" id="upload_image" accept="image/*" />
                        </div>
                    </div>
                    <div class="col-12 col-md-3" style="display:none" id='language'>
                        <div class="mb-3">
                            <label for="language" class="form-label">Language</label>
                            <select class="js-example-basic-multiple" name="languages[]" id="language" multiple="multiple">
                                @foreach ($languagesForFilter as $obj)
                                    <option value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3" style="display:none" id="fields">
                        <div class="mb-3">
                            <label for="fields" class="form-label">Field</label>
                            <select id="sel-fields" class="js-example-basic-multiple" name="fields[]" multiple="multiple">
                                @foreach ($fieldsForFilter as $obj)
                                    <option value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row border-top-1 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-3 d-flex">
                            <button type="reset" class="btn btn-primary-inverse w-100 mx-1">RESET</button>
                            <button type="button" class="btn btn-info w-100 mx-1" onclick="window.location.replace(`{{ route('users.index') }}`)">Cancel</button>
                            <button type="submit" class="btn btn-primary w-100 mx-1">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
<script>  
$(document).ready(function() {
    $('#role').on('change', function() {
      if ( this.value == '3')
      {
        $("#fields").show();
        $("#language").show();
      }
      else
      {
        $("#fields").hide();
        $("#language").hide();
      }
    });
    if ($("#role option:selected").val() == '3') {
        $("#fields").show();
        $("#language").show();
    } else{
        $("#fields").hide();
        $("#language").hide();
    }
});
</script>
@endsection