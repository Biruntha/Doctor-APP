@extends("layouts.main")

@section("main-body")
    <h1>Update role  - {{ $role->name }}</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger fade show">{{ $error }}</div>
        @endforeach
    @endif

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method("PUT")
        <div class="row" id="filter-panel">
            <div class="col-12">
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control mx-1" value="{{ $role->name }}" placeholder="Name" type="text" name="name" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input class="form-control mx-1" value="{{ $role->description }}" placeholder="Description" type="text" name="description" />
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
                                        $role->permissions->contains($permission->id)) checked @endif>
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
                        <div class="col-12 col-md-3 offset-md-9 d-flex">
                            <button type="reset" class="btn btn-primary-inverse w-100 mx-1">RESET</button>
                            <button type="button" class="btn btn-info w-100 mx-1" onclick="window.location.replace(`{{ route('roles.show',$role->id) }}`)">Cancel</button>
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
@endsection