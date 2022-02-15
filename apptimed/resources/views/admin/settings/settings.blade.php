@extends("layouts.main")

@section("main-body")
    <h1>Account Settings</h1>

    <form action="{{ route('update-setting') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-4">Reason Codes</h6>
                            <div class="mb-3">
                                <label for="order_flag_reasons" class="form-label">Order Flag Reasons (Separated by comma)</label>
                                <input class="form-control mx-1" value="{{$order_flag_reasons}}" placeholder="" type="text" name="order_flag_reasons" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="order_disapprove_reasons" class="form-label">Order Disapproval Reasons (Separated by comma)</label>
                                <input class="form-control mx-1" value="{{$order_disapprove_reasons}}" placeholder="" type="text" name="order_disapprove_reasons" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="order_cancel_reasons" class="form-label">Order Cancellation Reasons (Separated by comma)</label>
                                <input class="form-control mx-1" value="{{$order_cancel_reasons}}" placeholder="" type="text" name="order_cancel_reasons" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="inquiry_reasons" class="form-label">Inquiry Reasons (Separated by comma)</label>
                                <input class="form-control mx-1" value="{{$inquiry_reasons}}" placeholder="" type="text" name="inquiry_reasons" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-4 mt-5">Ahrefs Account Information</h6>
                            <div class="mb-3">
                                <label for="ahref_client_id" class="form-label">Ahrefs Client Id</label>
                                <input class="form-control mx-1" value="{{$ahrefs_client_id}}" placeholder="Ahrefs Client Id" type="text" name="ahref_client_id" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="ahref_client_secret" class="form-label">Ahrefs Client Secret</label>
                                <input class="form-control mx-1" value="{{$ahrefs_client_secret}}" placeholder="Ahrefs Client Secret" type="text" name="ahref_client_secret" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="ahref_redirect_uri" class="form-label">Ahrefs Redirect URI</label>
                                <input class="form-control mx-1" value="{{$ahrefs_redirect_uri}}" placeholder="Ahrefs Redirect URI" type="text" name="ahref_redirect_uri" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h6 class="mb-4 mt-5">Paypal Account Information</h6>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="paypal_clientid" class="form-label">Paypal Client Id</label>
                                <input class="form-control mx-1" value="{{$paypal_client_id}}" placeholder="Paypal Client Id" type="text" name="paypal_clientid" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="paypal_secret" class="form-label">Paypal Secret</label>
                                <input class="form-control mx-1" value="{{$paypal_secret}}" placeholder="Paypal Secret" type="text" name="paypal_secret" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="paypal_mode" class="form-label">Paypal Mode</label>
                                <input class="form-control mx-1" value="{{$paypal_mode}}" placeholder="Paypal Mode" type="text" name="paypal_mode" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h6 class="mb-4 mt-5">Facebook Conversion API Account Information</h6>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="pixelid" class="form-label">Conversion API Pixel ID</label>
                                <input class="form-control mx-1" value="{{$conversion_api_pixel_id}}" placeholder="Pixel ID" type="text" name="pixelid" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="con_accesstoken" class="form-label">Conversion API Accesstoken</label>
                                <input class="form-control mx-1" value="{{$conversion_api_accesstoken}}" placeholder="Access token" type="text" name="con_accesstoken" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="event_code" class="form-label">Conversion API Event Code</label>
                                <input class="form-control mx-1" value="{{$conversion_api_test_event_code}}" placeholder="Event Code" type="text" name="event_code" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h6 class="mb-4 mt-5">DataForSeo Task/Site Information</h6>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="last_site_id" class="form-label">Last Updated Site Id</label>
                                <input class="form-control mx-1" value="{{$last_updated_site_id}}" placeholder="Site ID" type="text" name="last_site_id" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="last_task_id" class="form-label">Last Updated Task Id</label>
                                <input class="form-control mx-1" value="{{$last_updated_task_id}}" placeholder="Task Id" type="text" name="last_task_id" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="task_count" class="form-label">Site/Task Count</label>
                                <input class="form-control mx-1" value="{{$site_or_task_count}}" placeholder="Site/Task Count" type="text" name="task_count" />
                            </div>
                        </div>
                    </div>
                <div class="col-md-3">
                    <button type="submit" class="btn p-3 btn-success w-100 mx-1 mb-4">UPDATE</button>
                </div>
            </div>
        </div>
    </form>
@endsection