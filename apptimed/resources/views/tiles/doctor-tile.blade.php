<div class="col-sm-{{$smSize != null ? $smSize : 12}} col-md-{{$mdSize != null ? $mdSize : 6}} col-xl-{{$xlSize != null ? $xlSize : 4}}">
    <a href="/doctors/{{$obj->code}}">
        <div class="mb-4 p-2 bg-white rounded shadow-md vacancy-card">
            <div class="d-flex">
                <div class="img-cont">
                    @if($obj->image == null)
                    <img alt="doctor-img" src="data:image/png;base64,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" />
                    @else
                    <img alt="doctor-img" src="{{asset('storage/UserImages/'.$obj->image)}}" />
                    @endif
                </div>
                <div class="details">
                    <h4>{{ $obj->fullname }}</h4>
                    <p>{{ $obj->title }}</p>
                    <p>{{ $obj->registration_id }}</p>
                    <p class="field">Member since {{ explode(" ", $obj->jdate)[0] }}</p>
                </div>
            </div>
            <div class="d-flex more-details">
                <div class="detail-cont"><i class="far fa-users"></i> Followers</div>
                <div class="detail-cont"><i class="far fa-briefcase"></i> </div>       
            </div>
            <div class="d-flex actions">
                <div class="detail-cont"><i class="far fa-share-square"></i> Share</div>
                <div class="action-cont bg-grad text-white"><i class="far fa-arrow-right"></i> View Profile</div>       
            </div>
        </div>
    </a>
</div>