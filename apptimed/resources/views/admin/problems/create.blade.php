@extends("layouts.main")

@section("main-body")
    <h1 class="page-heading rounded">Create Problem</h1>

    <form action="{{ route('problems.store') }}" method="POST">
        @csrf
        <div class="row" id="filter-panel">
            <div class="col-12">
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <strong style="color:red">*</strong></label>
                                <input class="form-control mx-1" value="{{old('name')}}" placeholder="Name" type="text" name="name" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="problem_type" class="form-label">Problem Type <strong style="color:red">*</strong></label>
                                <select class="form-control" name="problem_type" id="problem_type">
                                    <option value="">All</option>
                                    @foreach ($problemTypes as $obj)
                                        <option {{old('problem_type') == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control mx-1" placeholder="Description" type="text" name="description">{{old('description')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top-1 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-9 d-flex">
                            <button type="reset" class="btn btn-primary-inverse w-100 mx-1">RESET</button>
                            <button type="submit" class="btn btn-primary w-100 mx-1">SAVE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection