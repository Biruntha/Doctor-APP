@extends("layouts.main")

@section("main-body")
    <h1 class="page-heading rounded">Update Specialization  - {{ $specialization->name }}</h1>

    <form action="{{ route('specializations.update', $specialization->id) }}" method="POST" enctype='multipart/form-data'>
        @csrf
        @method("PUT")
        <div class="row" id="filter-panel">
            <div class="col-12">
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control mx-1" value="{{ old('name', $specialization->name) }}" placeholder="Name" type="text" name="name" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control mx-1" placeholder="Description" type="text" name="description">{{ old('description', $specialization->description) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type='file' name='logo' class="form-control">
                                @if(isset($specialization->logo))
                                    <img src="{{asset('storage/Specialization/LogoImages/'.$specialization->logo)}}" class="img-thumbnail-app" alt="Gallery image"/>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row border-top-1 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-9 d-flex">
                            <button type="reset" class="btn btn-primary-inverse w-100 mx-1">RESET</button>
                            <button type="submit" class="btn btn-primary w-100 mx-1">UPDATE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection