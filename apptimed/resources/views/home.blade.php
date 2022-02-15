@extends("layouts.main-web")

@section("banner")
<div class="banner-cont bg-grad p-3 pt-5 pb-2 py-md-2 mb-3">
  <svg class="bubble left" viewBox="0 0 900 1200" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"><g transform="translate(472.583010492509 319.39517466412696)"><path d="M125.9 -180.1C162 -147 189.4 -108.7 204.7 -65.6C220 -22.5 223.2 25.5 215.5 76.4C207.7 127.2 189.1 180.9 151.4 199.9C113.6 218.9 56.8 203.2 9.8 189.7C-37.2 176.2 -74.5 165 -116 147.2C-157.5 129.5 -203.3 105.2 -232.3 64.1C-261.2 23.1 -273.3 -34.8 -258.1 -84.6C-242.9 -134.3 -200.5 -176 -153 -205.4C-105.4 -234.8 -52.7 -251.9 -3.9 -246.5C44.9 -241.1 89.7 -213.2 125.9 -180.1" fill="#ffffff"></path></g></svg>
    
  <img src="/assets/images/banner1.png" alt="alzawaj" class="banner-img banner1 d-none d-lg-block">
  <img src="/assets/images/banner2.png" alt="alzawaj" class="banner-img banner2 d-none d-lg-block">

  <svg viewBox="0 0 10 2" class="d-none d-md-block">
    <text x="5" y="1" text-anchor="middle" font-size="1" fill="#fff" stroke-width=".008" stroke="none" font-family="poppins" style="font-weight:bolder;opacity:0.07">ALZAWAJ</text>
  </svg>

  <h1 class="text-center text-white mt-n100">The largest Muslim Matrimony <br class="d-none d-md-block">in Sri Lanka</h1>
  <h5 class="text-center text-white mt-2">Find your perfect match. With ease. </h5>
</div>
@endsection

@section("main-body")

<form method="GET" action="/">
  <input name="search" type="hidden" value="" />
<div class="row mb-5">
    <div class="col-md-8 offset-md-2 col-sm-10 offset-sm-1" style="margin-top:-100px;">
      <div class="my-3 p-3 p-md-4 bg-body rounded shadow-sm">
        <div class="row">
          <div class="col-lg-4">
              <div class="position-relative form-group">
                  <select class="form-control fw-bold font-120 my-2" id="gender" name="gender">
                    <option value="">I'm Looking for?</option>
                    <option value="Male">Groom</option>
                    <option value="Female">Bride</option>
                  </select>
              </div>                                                          
          </div>
          <div class="col-lg-4">
              <div class="position-relative form-group">
                  <select class="form-control fw-bold font-120 my-2" id="attribute_10" name="attribute_10">
                    <option value="">Local or Foreign?</option>
                    <option value="">Any</option>
                    <option value="36">Local</option>
                    <option value="37">Foreign</option>
                  </select>
              </div>                                                          
          </div>
          <div class="col-lg-4">
              <button type="submit" class="btn btn-warning w-100 font-white rounded-sm fw-bold my-2" style="border-radius:5px;height:42px">
                <i class="fa fa-search me-2"></i> Find Matches
              </button>
          </div>
        </div>
      </div>
    </div>
</div>
</form>

<section class="container mt-5 py-5">
  <div class="row mb-5">
    <h3 class="fw-bold text-center text-theme">Finding your Life Partner made easier with Alzawaj</h3>
    <p class="text-center font-120 mt-3">
      Registering with any of the matrimonial site is not enough to get the best responses on your matrimonial profile. You should always focus on other information as well. You should always try to fill all the relative information and important information. Besides, updating the information in the given fields you must write an impressive matrimonial profile description with stunning photos. A smartly written profile description is very much necessary to attract right kind of responses.
    </p>

    <p class="text-center font-120 mt-3">
      Registering with any of the matrimonial site is not enough to get the best responses on your matrimonial profile. You should always focus on other information as well. You should always try to fill all the relative information and important information. Besides, updating the information in the given fields you must write an impressive matrimonial profile description with stunning photos. A smartly written profile description is very much necessary to attract right kind of responses.
    </p>
  </div>
</section>

@endsection