@extends("layouts.main")

@section("main-body")
    <h1>Create Role</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger fade show">{{ $error }}</div>
        @endforeach
    @endif

    <form action="{{ route('roles.store') }}" method="POST">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    @csrf
                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control mx-1" placeholder="Name" type="text" name="name" />
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input class="form-control mx-1" placeholder="Description" type="text" name="description" />
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                            <div class="mb-3">
                                <label for="authorities" class="form-label">Authorities</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <fieldset>
                                <legend></legend>
                                <label class="checkboxLabel"><label>Check All Authorities</label>
                                    <input type="checkbox" id="check-all" onchange="onChangeClickCheckAll('all')">
                                    <span class="checkmark"></span>
                                </label>
                            </fieldset>
                        </div>
                    </div>
                    <!-- <br> -->
                    <div class="row">
                        @foreach($types as $per_type)
                            <div class="col-md-3" style="padding-top:10px">
                                <div class="form-group">
                                    <fieldset>
                                        <legend><label>{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}</legend></label>
                                        <label class="checkboxLabel"><label>Check All Authorities For {{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}</label>
                                        <input type="checkbox"  class="check-all-permission" id='check-{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}' onchange="onChangeClickCheckAll('{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}')">
                                            <span class="checkmark"></span>
                                        </label>
                                        @foreach($permissions as $permission)
                                            @if($per_type->type == $permission->type)
                                                <label class="checkboxLabel">{{ucwords(strtolower(str_replace('-',' ',str_replace('CAN',' ',str_replace($per_type->type,' ',$permission->name)))))}}
                                                    <input type="checkbox" class="check-all-permission check-box-click-{{$per_type->type}}"  name="permission[]" value="{{$permission->id}}"  @if(is_array(old('permission')) && in_array($permission->id,old('permission')) ) checked @endif>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </fieldset>
                                            @endif
                                        @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row border-top-1 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-9 d-flex">
                            <button type="reset" class="btn btn-primary-inverse w-100 mx-1">RESET</button>
                            <button type="button" class="btn btn-info w-100 mx-1" onclick="window.location.replace(`{{ route('roles.index') }}`)">Cancel</button>
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
        $('#checkall').click(function(event) {  //on click
            if(this.checked) { // check select status
                $('.authority').each(function() { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"
                });
            }else{
                $('.authority').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"
                });
            }
            var chkArray = [];
            $("input[name='check[]']:checked").map(function() {
                chkArray.push(this.value);
            }).get();
            var selected;
            selected = chkArray.join(',') + ",";
            if(selected.length > 1){
                // alert('Selecionar todos?');
            } else {}
        });

    });

    function onChangeClickCheckAll(val){
    id = `check-${val}`;
    if(val=='all'){
        if($(`#${id}`).is(':checked')){
            $('.check-all-permission').prop('checked',true);
        }
        else{
            $('.check-all-permission').prop('checked',false);
        }
    }
    else{
        if($(`#${id}`).is(':checked')){
            $(`.check-box-click-${val}`).prop('checked',true);
        }
        else{
            $(`.check-box-click-${val}`).prop('checked',false);
        }
    }
}
</script>
@endsection