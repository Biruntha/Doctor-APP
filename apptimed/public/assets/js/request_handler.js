function sendHttpRequest(url, method, data, callback, isToShowLoader = true, async = true) {
    var result = {};

    if (isToShowLoader)
        $("#page-loader").show();

    setTimeout(function() {

        try {
            //data["_token"]  =  $('meta[name="csrf-token"]').attr('content');
            console.log(data);

            $.ajax({
                type: method,
                dataType: 'json',
                processData: false,
                contentType: 'application/json',
                cache: false,
                url: url,
                timeout: 40000,
                async: async,
                data: JSON.stringify(data),
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response, textStatus) {
                    result.data = response;
                    result.is_success = true;
                    result.msg = "Request was successful";

                    //console.log(result);
                    if (isToShowLoader) {
                        $("#page-loader").fadeOut();
                    }
                    callback(result);

                },
                fail: function(xhr, textStatus, errorThrown) {
                    result.is_success = false;
                    result.msg = "Sorry something went wrong. Please check your Internet connection.";
                    result.exception = errorThrown;
                    console.log(xhr);
                    if (isToShowLoader) {
                        $("#page-loader").fadeOut();
                    }
                    callback(result);

                },
                error: function(xhr, textStatus) {
                    console.log(xhr);
                    result.is_success = false;
                    result.msg = "";
                    if (xhr.status == 422) {
                        var response = JSON.parse(xhr.responseText);
                        if (Object.values(response.errors)[0][0] != undefined && Object.values(response.errors)[0][0] != null) {
                            result.msg = Object.values(response.errors)[0][0];
                        } else {
                            result.msg = response.message;
                        }

                    } else {
                        result.msg = "Sorry something went wrong. Please check your Internet connection.";
                    }
                    if (isToShowLoader) {
                        $("#page-loader").fadeOut();
                    }
                    callback(result);

                }
            });
        } catch (err) {
            result.is_success = false;
            result.msg = "Sorry something went wrong. Please check your Internet connection.";
            result.exception = err;
            console.log(err);
            callback(result);
            if (isToShowLoader) {
                $("#page-loader").fadeOut();
            }
        }
    }, 200);
}

function sendHttpPollRequest(url, method, data, callback, isToShowLoader = false) {
    var result = {};
    try {
        /**console.log('====================================');
        console.log(data);
        console.log('====================================');**/
        $.ajax({
            type: method,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            url: url,

            data: data,
            headers: {
                'Accept': 'application/json',
                // 'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('sessionToken')
            },
            success: function(response, textStatus) {
                result.data = response;
                result.is_success = true;
                result.msg = "Request was successful";

                //console.log(result);
                callback(result);
                // $("#page-loader").fadeOut();
            },
            fail: function(xhr, textStatus, errorThrown) {
                result.is_success = false;
                result.msg = "Sorry something went wrong. Please check your Internet connection.";
                result.exception = errorThrown;
                console.log(xhr);
                callback(result);
                // $("#page-loader").fadeOut();
            },
            error: function(xhr, textStatus) {
                console.log(xhr);
                result.is_success = false;
                result.msg = "";
                if (xhr.status == 422) {
                    var response = JSON.parse(xhr.responseText);
                    result.msg = response.errors;
                } else {
                    result.msg = "Sorry something went wrong. Please check your Internet connection.";
                }
                callback(result);
                // $("#page-loader").fadeOut();
            }
        });
    } catch (err) {
        result.is_success = false;
        result.msg = "Sorry something went wrong. Please check your Internet connection.";
        result.exception = err;
        console.log(err);
        callback(result);
        // $("#page-loader").fadeOut();
    }
}


function sendhttpPostFormData(url, formDataId, method, callback, isToShowLoader = false) {
    var result = {};
    var formData = new FormData($(`#${formDataId}`)[0]);

    if (isToShowLoader)
        $("#page-loader").show();

    setTimeout(function() {
        try {
            data.token = getSessionToken();

            $.ajax({
                type: method,
                dataType: 'json',
                url: url,
                timeout: 5000,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + localStorage.getItem('sessionToken')
                },
                success: function(response, textStatus) {
                    result.data = response;
                    result.is_success = true;
                    result.msg = "Request was successful";

                    //console.log(result);
                    callback(result);
                    $("#page-loader").fadeOut();
                },
                fail: function(xhr, textStatus, errorThrown) {
                    result.is_success = false;
                    result.msg = "Sorry something went wrong. Please check your Internet connection.";
                    result.exception = errorThrown;
                    console.log(result);
                    callback(result);
                    $("#page-loader").fadeOut();
                },
                error: function(xhr, textStatus) {
                    result.is_success = false;
                    result.msg = "Sorry something went wrong. Please check your Internet connection.";
                    console.log(xhr);
                    callback(result);
                    $("#page-loader").fadeOut();
                }
            });
        } catch (err) {
            result.is_success = false;
            result.msg = "Sorry something went wrong. Please check your Internet connection.";
            result.exception = err;
            //console.log(result);
            callback(result);
            $("#page-loader").fadeOut();
        }
    }, 200);
}