let dataTableObj = null;
var form = null;

$(document).ready(function () {
    $("#page-loader").hide();
    $("#btn-filter").on("click", function () {
        $('#filter-panel').slideToggle();
    });

    $("#btn-import").on("click", function () {
        $('#import-panel').slideToggle();
    });

    $(".confirm-action").on("submit", function (e) {
        form = this;
        console.log(form);
        e.preventDefault();
        swal({
            title: "Confirmation",
            text: "Are you sure that you want to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#E77300",
            buttons: [
                'No',
                'Yes'
            ],
            dangerMode: true,
        }).then(function (result) {
            console.log("actions");
            if (result.value) {
                console.log("ok");
                form.submit();
            } else {
                console.log("no");
                return;
            }
        });
    });

    $(".confirm-action-link").on("click", function () {
        ev.preventDefault();
        var urlToRedirect = ev.currentTarget.getAttribute('href');
        console.log(urlToRedirect);
        swal({
            title: "Confirmation",
            text: "Are you sure that you want to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#E77300",
            buttons: [
                'No',
                'Yes'
            ],
            dangerMode: true,
        }).then(function (result) {
            if (result.value) {
                swal("Success", {
                    icon: "success",
                });
            } else {
                //swal("Your imaginary file is safe!");
            }
        });
    });

    $('#recurring').change(function(){
        if($(this).is(":checked") == true)
            $('#recurring_details').show();
        else
            $('#recurring_details').hide();
        
    });

    $('.js-example-basic-multiple').select2();
});

function buildDataTable(scrollX = true, responsive = false) {
    
    //filling attributes for td columns in mobile view
    $.each($('#data-table thead tr th'), function(i, th){
        if($(th).text() != "" && $(th).text() != "Actions")
            $('#data-table tbody tr td:nth-child('+ (i+1) +')').attr("label", $(th).text() + ": ");
    });

    dataTableObj = $('#data-table').DataTable({
        "lengthMenu": [
            [20, 50, 100, -1],
            [20, 50, 100, "All"]
        ],
        "order": [],
        "language": {
            "info": "Showing _START_ to _END_ of _TOTAL_ records",
            "decimal": ".",
            "thousands": ","
        },
        
        colReorder: true,
        stateSave: true,
        scrollX: scrollX,
        'responsive': responsive,
        "columnDefs": [{
            "orderable": false,
            "targets": ["no-sort"]
        }]
    });

    $('#dlength-sel').on("change", function () {
        $('.dataTables_length select').val($('#dlength-sel').val());
        $('.dataTables_length select').trigger("change");
    });

    $('#txt-search').keyup(function () {
        dataTableObj.search($(this).val()).draw();
    });
}

function addToWishlist(ele, site) {
    if (!$(ele).hasClass("added")) {
        sendHttpRequest("/wishlist", "POST", {
            site: site
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);

                    if (typeof wishLists !== 'undefined')
                        wishLists.push(site);

                    //facebook pixel event
                    try{
                        let siteObj = siteMap[site];
                        fbq('track', 'AddToWishlist', {'contentId' : siteObj.code});
                    }
                    catch(err){
                        console.error(err);
                    }

                    $(ele).parent().find(".icon-wlist.added").show();
                    $(ele).hide();
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    } else {
        sendHttpRequest("/wishlist/remove", "POST", {
            site: site
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $(ele).parent().find(".icon-wlist.open").show();
                    $(ele).hide();

                    if (typeof wishLists !== 'undefined') {
                        var index = wishLists.indexOf(site);
                        if (index !== -1) {
                            wishLists.splice(index, 1);
                        }
                    }
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    }
}

function addToCart(ele, site, isToReloadPage = false) {
    if (!$(ele).hasClass("added")) {
        sendHttpRequest("/cart", "POST", {
            site: site
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    carts.push(site);
                    $(ele).parent().find(".icon-cart.added").show();
                    $(ele).hide();

                    if(isToReloadPage)
                        location.reload();

                    //facebook pixel event
                    try{
                        let siteObj = siteMap[site];
                        fbq('track', 'AddToCart', {'contentId' : siteObj.code});
                    }
                    catch(err){
                        console.error(err);
                    }

                    try{
                        $(".cart-count").html(carts.length + "");
                    }
                    catch(err){}
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    } else {
        sendHttpRequest("/cart/remove", "POST", {
            site: site
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $(ele).parent().find(".icon-cart.open").show();
                    $(ele).hide();

                    if(isToReloadPage)
                        location.reload();

                    var index = carts.indexOf(site);
                    if (index !== -1) {
                        carts.splice(index, 1);
                    }

                    try{
                        $(".cart-count").html(carts.length + "");
                    }
                    catch(err){}
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    }
}

function showTimeslotModal(year, month, day) {
    var day = ("0" + day).slice(-2);
    var month = ("0" + month).slice(-2);
    let today = year  + '-' + month + '-' + day;

    $("#timeslotAddModalTitle").html("Add Timeslot for " + today);
    $("#add-timeslots").trigger("reset");
    $("#year_val").val(year);
    $("#month_val").val(month);
    $("#day_val").val(day);

    $("#doctor_date").val(today);

    $("#timeslotAddModal").modal("show");
}

function saveTimeSlot()
{
    let doctor_date = $("#doctor_date").val();
    let doctor_time_from = $("#doctor_time_from").val();
    let doctor_time_to = $("#doctor_time_to").val();
    let no_of_appointments = $("#no_of_appointments").val();
    let recurring = $("#recurring").is(":checked");
    let doctor_end_date = $("#doctor_end_date").val();
    let week_days = $("#week_days").val();
    let isValidated = true;
console.log(doctor_date);
    if(doctor_date == null || doctor_date.length == 0) {
        $("#timeslot-alert").html("Timeslot Start Date can't be empty");
        isValidated = false;
    } else if(doctor_time_from == null || doctor_time_from.length == 0) {
        $("#timeslot-alert").html("Timeslot Start time can't be empty");
        isValidated = false;
    } else if(doctor_time_to == null || doctor_time_to.length == 0) {
        $("#timeslot-alert").html("Timeslot End time can't be empty");
        isValidated = false;
    } else if (recurring == true && (doctor_end_date == null || doctor_end_date.length == 0)) {
        console.log(doctor_end_date);
        $("#timeslot-alert").html("Timeslot End Date can't be empty");
        isValidated = false;
    } else if (recurring == true && week_days.length == 0) {
        $("#timeslot-alert").html("Scheduling days need to be selected");
        isValidated = false;
    } else if (recurring == true && doctor_date >= doctor_end_date) {
        $("#timeslot-alert").html("End date need to be grater than Start date");
        isValidated = false;
    }

    if(!isValidated){
        $("#timeslot-alert").slideDown();

        setTimeout(() => {
        $("#timeslot-alert").slideUp();
        }, 5000);
    } else {

        let data = {
            doctor_date: doctor_date,
            doctor_time_from: doctor_time_from,
            doctor_time_to: doctor_time_to,
            no_of_appointments: no_of_appointments,
            recurring: recurring,
            doctor_end_date: doctor_end_date,
            week_days: week_days,
        }
        sendHttpRequest("/doctor/timeslots", "POST", data, function (res) {
            console.log(res);
            if (res.is_success) {
                if (res.data.is_success) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $("#timeslotAddModal").modal("hide");
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
                } else {
                    // $("#failedToast").toast("show");
                    // $("#failedToast .toast-body").html(res.data.msg);
                    $("#timeslot-alert").html(res.data.msg);
                    $("#timeslot-alert").slideDown();
                    setTimeout(() => {
                        $("#timeslot-alert").slideUp();
                    }, 5000);
                }
            }
        }, isToShowLoader = true);
    }
}

function editTimeslotModal(id, date, start_time, end_time, no_of_appointments) {
    $("#timeslotEditModalTitle").html("Edit Timeslot for " + date);
    $("#edit-timeslots").trigger("reset");
    $("#timeslot_date").val(date);
    $("#timeslot_start_time").val(start_time);
    $("#timeslot_end_time").val(end_time);
    $("#timeslot_appointments").val(no_of_appointments);
    $("#timeslot_id").val(id);

    $("#timeslotEditModal").modal("show");
}

function updateTimeSlot()
{
    let timeslot_id = $("#timeslot_id").val();
    let timeslot_date = $("#timeslot_date").val();
    let timeslot_start_time = $("#timeslot_start_time").val();
    let timeslot_end_time = $("#timeslot_end_time").val();
    let timeslot_appointments = $("#timeslot_appointments").val();
    let isValidated = true;

    if(timeslot_date == null || timeslot_date.length == 0) {
        $("#timeslot-edit-alert").html("Timeslot Date can't be empty");
        isValidated = false;
    } else if(timeslot_start_time == null || timeslot_start_time.length == 0) {
        $("#timeslot-edit-alert").html("Timeslot Start time can't be empty");
        isValidated = false;
    } else if(timeslot_end_time == null || timeslot_end_time.length == 0) {
        $("#timeslot-edit-alert").html("Timeslot End time can't be empty");
        isValidated = false;
    }

    if(!isValidated){
        $("#timeslot-edit-alert").slideDown();

        setTimeout(() => {
        $("#timeslot-edit-alert").slideUp();
        }, 5000);
    } else {

        let data = {
            timeslot_date: timeslot_date,
            timeslot_start_time: timeslot_start_time,
            timeslot_end_time: timeslot_end_time,
            timeslot_appointments: timeslot_appointments,
        }
        sendHttpRequest("/doctor/update-timeslot/" + timeslot_id, "POST", data, function (res) {
            console.log(res);
            if (res.is_success) {
                if (res.data.is_success) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $("#timeslotEditModal").modal("hide");
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
                } else {
                    // $("#failedToast").toast("show");
                    // $("#failedToast .toast-body").html(res.data.msg);
                    $("#timeslot-edit-alert").html(res.data.msg);
                    $("#timeslot-edit-alert").slideDown();
                    setTimeout(() => {
                        $("#timeslot-edit-alert").slideUp();
                    }, 5000);
                }
            }
        }, isToShowLoader = true);
    }
}

function removeTimeslotModal() {
    $("#timeslotDeleteModalTitle").html("Delete Timeslot");
    $("#delete-timeslots").trigger("reset");

    $("#timeslotDeleteModal").modal("show");
}

function deleteTimeSlot() {
    let delete_start_date = $("#delete_start_date").val();
    let delete_end_date = $("#delete_end_date").val();
    let isValidated = true;

    if(delete_start_date == null || delete_start_date.length == 0) {
        $("#timeslot-delete-alert").html("Start Date can't be empty");
        isValidated = false;
    } else if(delete_end_date == null || delete_end_date.length == 0) {
        $("#timeslot-delete-alert").html("End Date can't be empty");
        isValidated = false;
    } else if(delete_start_date >= delete_end_date) {
        $("#timeslot-delete-alert").html("End Date need to be grater than Start Date");
        isValidated = false;
    }

    if(!isValidated){
        $("#timeslot-delete-alert").slideDown();

        setTimeout(() => {
        $("#timeslot-delete-alert").slideUp();
        }, 5000);
    } else {

        let data = {
            delete_start_date: delete_start_date,
            delete_end_date: delete_end_date,
        }
        sendHttpRequest("/doctor/delete-timeslots", "POST", data, function (res) {
            console.log(res);
            if (res.is_success) {
                if (res.data.is_success) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $("#timeslotDeleteModal").modal("hide");
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
                } else {
                    // $("#failedToast").toast("show");
                    // $("#failedToast .toast-body").html(res.data.msg);
                    $("#timeslot-delete-alert").html(res.data.msg);
                    $("#timeslot-delete-alert").slideDown();
                    setTimeout(() => {
                        $("#timeslot-delete-alert").slideUp();
                    }, 5000);
                }
            }
        }, isToShowLoader = true);
    }
}

function addQualification()
{
    let qualification = $("#qualification").val();
    let year = $("#year").val();
    let isValidated = true;

    if(qualification == null || qualification.length == 0) {
        $("#qualification-alert").html("Qualification can't be empty");
        isValidated = false;
    }

    if(!isValidated){
        $("#qualification-alert").slideDown();

        setTimeout(() => {
        $("#qualification-alert").slideUp();
        }, 5000);
    } else {

        let data = {
            qualification: qualification,
            year: year,
        }
        sendHttpRequest("/doctor/qualification", "POST", data, function (res) {
            console.log(res);
            if (res.is_success) {
                if (res.data.is_success) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $("#qualification-modal").modal("hide");
                    setTimeout(() => {
                        window.location.assign('/edit-profile');
                    }, 3000); 
                } else {
                    // $("#failedToast").toast("show");
                    // $("#failedToast .toast-body").html(res.data.msg);
                    $("#qualification-alert").html(res.data.msg);
                    $("#qualification-alert").slideDown();
                    setTimeout(() => {
                        $("#qualification-alert").slideUp();
                    }, 5000);
                }
            }
        }, isToShowLoader = true);
    }
}

function addSpecialization()
{
    let specialization = $("#specialization").val();
    let experience_year = $("#experience_year").val();
    let note = $("#note").val();
    let isValidated = true;

    if(specialization == null || specialization.length == 0) {
        $("#specialization-alert").html("Specialization need to be selected");
        isValidated = false;
    }

    if(!isValidated){
        $("#specialization-alert").slideDown();

        setTimeout(() => {
        $("#specialization-alert").slideUp();
        }, 5000);
    } else {

        let data = {
            specialization: specialization,
            experience_year: experience_year,
            note: note,
        }
        sendHttpRequest("/doctor/specialization", "POST", data, function (res) {
            console.log(res);
            if (res.is_success) {
                if (res.data.is_success) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $("#specialization-modal").modal("hide");
                    setTimeout(() => {
                        window.location.assign('/edit-profile');
                    }, 3000); 
                } else {
                    // $("#failedToast").toast("show");
                    // $("#failedToast .toast-body").html(res.data.msg);
                    $("#specialization-alert").html(res.data.msg);
                    $("#specialization-alert").slideDown();
                    setTimeout(() => {
                        $("#specialization-alert").slideUp();
                    }, 5000);
                }
            }
        }, isToShowLoader = true);
    }
}

function editDoctorQualificationModal(id, qualification, year) {
    $("#qualificationEditModalTitle").html("Edit Qualification - " + qualification);
    $("#edit-qualification").trigger("reset");
    $("#doc_qualification").val(qualification);
    $("#doc_qualification_year").val(year);
    $("#doc_qualification_id").val(id);

    $("#qualificationEditModal").modal("show");
}

function updateQualification()
{
    let doc_qualification_id = $("#doc_qualification_id").val();
    let doc_qualification = $("#doc_qualification").val();
    let doc_qualification_year = $("#doc_qualification_year").val();
    let isValidated = true;

    if(doc_qualification == null || doc_qualification.length == 0) {
        $("#edit-qualification-alert").html("Qualification can't be empty");
        isValidated = false;
    }

    if(!isValidated){
        $("#edit-qualification-alert").slideDown();

        setTimeout(() => {
            $("#edit-qualification-alert").slideUp();
        }, 5000);
    } else {

        let data = {
            qualification: doc_qualification,
            qualification_year: doc_qualification_year,
        }
        sendHttpRequest("/doctor/qualification/" + doc_qualification_id, "POST", data, function (res) {
            console.log(res);
            if (res.is_success) {
                if (res.data.is_success) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $("#qualificationEditModal").modal("hide");
                    setTimeout(() => {
                        window.location.assign('/edit-profile');
                    }, 3000); 
                } else {
                    $("#edit-qualification-alert").html(res.data.msg);
                    $("#edit-qualification-alert").slideDown();
                    setTimeout(() => {
                        $("#edit-qualification-alert").slideUp();
                    }, 5000);
                }
            }
        }, isToShowLoader = true);
    }
}

function editDoctorSpecializationModal(id, specialization, experience_year, note) {
    console.log(specialization);
    $("#specializationEditModalTitle").html("Edit Specialization - " + specialization);
    $("#edit-specialization").trigger("reset");
    $("#doc_specialization > [value=" + specialization + "]").attr("selected", "true");
    $("#doc_experience_year").val(experience_year);
    $("#doc_note").val(note);
    $("#doc_specialization_id").val(id);

    $("#specializationEditModal").modal("show");
}

function updateSpecialization()
{
    let doc_specialization_id = $("#doc_specialization_id").val();
    let doc_specialization = $("#doc_specialization").val();
    let doc_experience_year = $("#doc_experience_year").val();
    let doc_note = $("#doc_note").val();
    let isValidated = true;

    if(doc_specialization == null || doc_specialization.length == 0) {
        $("#edit-specialization-alert").html("Specialization can't be empty");
        isValidated = false;
    }

    if(!isValidated){
        $("#edit-specialization-alert").slideDown();

        setTimeout(() => {
            $("#edit-specialization-alert").slideUp();
        }, 5000);
    } else {

        let data = {
            specialization: doc_specialization,
            experience_year: doc_experience_year,
            note: doc_note,
        }
        sendHttpRequest("/doctor/specialization/" + doc_specialization_id, "POST", data, function (res) {
            console.log(res);
            if (res.is_success) {
                if (res.data.is_success) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $("#specializationEditModal").modal("hide");
                    setTimeout(() => {
                        window.location.assign('/edit-profile');
                    }, 3000); 
                } else {
                    $("#edit-specialization-alert").html(res.data.msg);
                    $("#edit-specialization-alert").slideDown();
                    setTimeout(() => {
                        $("#edit-specialization-alert").slideUp();
                    }, 5000);
                }
            }
        }, isToShowLoader = true);
    }
}

function payByWallet()
{
    console.log('payByWallet====');
    let appointment_id = $("#w_appointment_id").val();
    let wallet_amount = $("#wallet_amount").val();
    let isValidated = true;
    let msg = '';

    if(appointment_id == null || appointment_id.length == 0) {
        isValidated = false;
        msg = "Appointment Id can't be empty";
    } else if(wallet_amount == null || wallet_amount.length == 0) {
        isValidated = false;
        msg = "Wallet amount can't be empty";
    }

    if(!isValidated) {
        $("#failedToast").toast("show");
        $("#failedToast .toast-body").html(msg);
    } else {

        let data = {
            appointment_id: appointment_id,
            wallet_amount: wallet_amount,
        }
        sendHttpRequest("/pay-with-wallet", "POST", data, function (res) {
            console.log(res);
            if (res.is_success) {
                if (res.data.is_success) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    
                    setTimeout(() => {
                        window.location.assign('/patient/appointments/'+res.data.id);
                    }, 3000); 
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                    if (res.is_success.status == "Failed") {
                        setTimeout(() => {
                            window.location.assign('/patient/timeslots/'+res.data.id);
                        }, 3000);
                    } else {
                        setTimeout(() => {
                            location.reload();
                        }, 3000); 
                    }
                }
            }
        }, isToShowLoader = true);
    }
}