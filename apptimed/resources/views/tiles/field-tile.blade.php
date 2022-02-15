
<div class="col-sm-{{$smSize != null ? $smSize : 12}} col-md-{{$mdSize != null ? $mdSize : 6}} col-xl-{{$xlSize != null ? $xlSize : 4}}">
    <a href="/vacancies?field={{$obj->id}}">
        <div class="mb-2 p-1 bg-white rounded shadow-md vacancy-card vacancy-card2">
            <div class="d-flex" style="position:relative">
                <div class="img-cont mt-1 ms-2">
                    <img alt="vacancy-img" src="/assets/images/favicon.png" />
                </div>
                <div class="details p-2">
                    <p>{{ $obj->name }} {!! $obj->is_highlighted ? "<i class='fas fa-star text-warning'></i>" : "" !!}</p>
                </div>
                    
                <div class="w-100 text-right" style="position:absolute">
                    <div class="more-details">
                        <div class="detail-cont detail-cont2 float-end text-dark px-3 fw-bold w-auto">{{ $obj->vacancyCount }}</div>     
                    </div>
                    <div class="actions text-right">
                        @if($obj->vacancyCount == 0)
                            <div class="action-cont bg-warning text-white w-auto px-3"><i class="far fa-arrow-right"></i> No vacancies</div>
                        @else
                            <div class="action-cont bg-grad text-white w-auto px-3"><i class="far fa-arrow-right"></i> View Vacancies</div>
                        @endif       
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>