@extends("layouts.main")

@section("main-body")
    <h1>Update User  - {{ $user->fname }} {{ $user->lname }}</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger fade show">{{ $error }}</div>
        @endforeach
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" files="true" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <div class="row" id="filter-panel">
            <div class="col-12">
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input class="form-control mx-1" value="{{ old('fname', $user->fname) }}" placeholder="First Name" type="text" name="fname" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input class="form-control mx-1" value="{{ old('lname', $user->lname) }}" placeholder="Last Name" type="text" name="lname" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" name="role" id="role">
                                    @foreach ($roles as $obj)
                                        <option value="{{$obj->id}}" {{old('role') == '' ? ($user->role == $obj->id ? 'selected' : '') : (old('role') == $obj->id ? 'selected' : '') }}>{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control mx-1" placeholder="Email" type="text" name="email" value="{{old('email', $user->email)}}" />
                            </div>
                        </div>
<!-- 
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input class="form-control mx-1" placeholder="Password" type="text" name="password" value="{{old('password')}}" />
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="lname" class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" id="upload_image" accept="image/*" />
                            </div>
                        </div> -->
                        <div class="col-12 col-md-3" style="display:none" id='language'>
                            <div class="mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="js-example-basic-multiple" name="languages[]" id="language" multiple="multiple">
                                    @foreach ($languagesForFilter as $obj)
                                        <option  {{ in_array($obj->id, $contentWriterLanguages) ? 'selected="selected"' : '' }} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-3" style="display:none" id="fields">
                            <div class="mb-3">
                                <label for="fields" class="form-label">Field</label>
                                <select id="sel-fields" class="js-example-basic-multiple" name="fields[]" multiple="multiple">
                                    @foreach ($fieldsForFilter as $obj)
                                        <option  {{ in_array($obj->id, $contentWriterFields) ? 'selected="selected"' : '' }} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Authorities</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <roleset>
                                    <legend></legend>
                                    <label class="checkboxLabel"><label>Check All Authorities</label>
                                        <input type="checkbox" id="check-all" onchange="onChangeClickCheckAll('all')">
                                        <span class="checkmark"></span>
                                    </label>
                                </roleset>
                            </div>
                        </div>
                    <!-- <br> -->
                    <div class="row" id="">
                        @foreach($types as $per_type)
                        <div class="col-md-3">
                            <div class="form-group">
                                <roleset>
                                    <legend><label>{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}</legend>
                                    </label>
                                    <label class="checkboxLabel"><label>Check All Authorities For
                                            {{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}</label>
                                        <input type="checkbox" class="check-all-permission"
                                            id='check-{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}'
                                            onchange="onChangeClickCheckAll('{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}')">
                                        <span class="checkmark"></span>
                                    </label>
                                    @foreach($permissions as $permission)
                                    @if($per_type->type == $permission->type)
                                    <label
                                        class="checkboxLabel">{{ucwords(strtolower(str_replace('-',' ',str_replace('CAN',' ',str_replace($per_type->type,' ',$permission->name)))))}}
                                        <input type="checkbox"
                                            class="check-all-permission check-box-click-{{$per_type->type}}"
                                            name="permission[]" value="{{$permission->id}}" @if(is_array(old('permission'))
                                            && in_array($permission->id,old('permission')) ||
                                        $user->permissions->contains($permission->id)) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </roleset>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    </div>
                    <div class="row border-top-1 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-3 d-flex">
                            <button type="reset" class="btn btn-primary-inverse w-100 mx-1">RESET</button>
                            <button type="button" class="btn btn-info w-100 mx-1" onclick="window.location.replace(`{{ route('users.show',$user->id) }}`)">Cancel</button>
                            <button type="submit" class="btn btn-primary w-100 mx-1">UPDATE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('scripts')
<script>
    function disableButton() {
        setTimeout(() => {
            $('#role-update-button').attr('disabled', 'disabled');
        }, 1500);
    }

    function onChangeClickCheckAll(val) {
        id = `check-${val}`;
        if (val == 'all') {
            if ($(`#${id}`).is(':checked')) {
                $('.check-all-permission').prop('checked', true);
            } else {
                $('.check-all-permission').prop('checked', false);
            }
        } else {
            if ($(`#${id}`).is(':checked')) {
                $(`.check-box-click-${val}`).prop('checked', true);
            } else {
                $(`.check-box-click-${val}`).prop('checked', false);
            }
        }
    }
</script>
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
    } else {
        $("#fields").hide();
        $("#language").hide();
    }
});
</script>
@endsection