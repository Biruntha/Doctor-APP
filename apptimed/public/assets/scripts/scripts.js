function askForConfirmation(formId) {
    Swal.fire({
	  title: "Are you sure?",
	  text: "Your will not be able to recover this imaginary file!",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonClass: "btn-danger",
	  confirmButtonText: "Yes, delete it!",
	  closeOnConfirm: false
    }).then((result) => {
        if (result.value) {
            $("#"+formId).submit();
        }
    });
}
