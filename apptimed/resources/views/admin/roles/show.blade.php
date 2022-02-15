@extends("layouts.main")

@section("main-body")
    <h1>View Role - {{ $role->name }}</h1>

        <div class="row" id="filter-panel">
            <div class="col-12">
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name : {{ $role->name }}</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description : {{ $role->description }}</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="mb-3">
                                <label for="authorities" class="form-label">Authorities :</label>
                            </div>
                        </div><!-- <br> -->
                        <div class="row" id="">
                            <div id="">
                            </div>

                            @foreach($types as $per_type)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <fieldset>
                                            <label>{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}</label>
                                                @foreach($permissions as $permission)
                                                @if($per_type->type == $permission->type)
                                                    <label class="checkboxLabel">{{ucwords(strtolower(str_replace('-',' ',str_replace('CAN',' ',str_replace($per_type->type,' ',$permission->name)))))}}
                                                        <input type="checkbox" name="permission[]" value="{{$permission->id}}" disabled="disabled" @if(is_array(old('permission')) && in_array($permission->id,old('permission')) || $role->permissions->contains($permission->id)) checked @endif>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </fieldset>
                                                @endif
                                            @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row border-top-1 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-9 d-flex">
                            <form action="{{ route('roles.destroy',$role->id) }}" method="POST" class="mx-1" id="delete-form">
                                @csrf
                                @method('DELETE')
                            </form>
                            {{--<button onclick="askForConfirmation('delete-form');" class="btn btn-primary-inverse w-100 mx-1">Delete</button>--}}
                            <button type="button" class="btn btn-info w-100 mx-1" onclick="window.location.replace(`{{ route('roles.index') }}`)">Back</button>
                            <button class="btn btn-primary w-100 mx-1" onclick="window.location.replace(`{{ route('roles.edit',$role->id) }}`)">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
<script>
function permissionPrint(permission = [])
    {
        $(".add-permision").html("");
        let all = `
    <div class="col-4">
    <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input permissions_add" id="checkedAll">
        <label class="form-check-label" style="font-size: small;" for="checkedAll">select all</label>
    </div>
    </div>
    <div class="col-4">
    <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input permissions_add" id="readOnly">
        <label class="form-check-label " style="font-size: small;" for="readOnly">Read Only</label>
    </div>
    </div>
    <div class="col-4">`;

        $(".add-permision").append(all);

        $.each(permisionData, function (key, ui) {
            $.each(ui, function (name, value) {
                let checkbox = "";
                $.each(value, function (ke, per) {
                    let cl = "readAndWrite";
                    if (per.name === "VIEW") {
                        cl = 'readOnlySingle';
                    }
                    checkbox = checkbox + `<div class="col-md-2">
                    <div class="form-check">
                        <input type="checkbox"
                            class="form-check-input permissions_add checkSingle text-permision ${cl}  master${key}"
                            onchange="childCheck(this,'master${key}','pres${key}')" name="permision[]" value="${per.id}"
                            id="child${per.id}">
                        <label class="form-check-label" style="font-size: small;" for="child${per.id}">` + per
                        .name + `</label>
                    </div>
                </div>`;
                })

                htmlStr = ` <div class="row per-cont">
                <div class="col-md-4">
                    <input type="checkbox" class="form-check-input permissions_add checkSingle "
                        onchange="masterCheck(this,'${key}')" id="pres${key}">
                    <label class="form-check-label" style="font-size: small;" for="pres${key}">` + name + `</label>
                </div>
                ${checkbox}
            </div>`;
                $(".add-permision").append(htmlStr);
            });
        });


        $("#checkedAll").change(function () {
            if (this.checked) {
                $(".checkSingle").each(function () {
                    this.checked = true;
                });
                $("#readOnly").prop("checked", false);
            } else {
                $(".checkSingle").each(function () {
                    this.checked = false;
                });
            }
        });

        $(".checkSingle").click(function () {
            if ($(this).is(":checked")) {
                var isAllChecked = 0;

                $(".checkSingle").each(function () {
                    if (!this.checked)
                        isAllChecked = 1;
                });

                if (isAllChecked == 0) {
                    $("#checkedAll").prop("checked", true);
                }
            } else {
                $("#checkedAll").prop("checked", false);
            }
        });

        $("#readOnly").change(function () {
            if (this.checked) {

                $(".checkSingle").each(function () {
                    this.checked = false;
                });
                $(".readOnlySingle").each(function () {
                    this.checked = true;
                });
                $("#checkedAll").prop("checked", false);

            } else {
                $(".readOnlySingle").each(function () {
                    this.checked = false;
                });
            }
        });

        $(".readOnlySingle").click(function () {
            if ($(this).is(":checked")) {
                var isAllChecked = 0;

                $(".readOnlySingle").each(function () {
                    if (!this.checked)
                        isAllChecked = 1;
                });

                if (isAllChecked == 0) {
                    $("#readOnly").prop("checked", true);
                }
            } else {
                $("#readOnly").prop("checked", false);
            }
        });

        $(".readAndWrite").click(function () {
            if (this.checked) {
                $("#readOnly").prop("checked", false);

            } else {

            }
        });

        if (permission.length != 0) {
            $.each(permission, function (key, ui) {
                id = ui.id;
                // $("#child"+id).prop("checked", true);
                $("#child" + id).click();
            });
        }

    }
</script>
@endsection