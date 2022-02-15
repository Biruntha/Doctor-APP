@extends("layouts.main")

@section("main-body")
<h1 class="page-heading text-center d-block">Upload Bank Slip - Appointment #{{$appointment->appointment_number}}</h1>

<form action="{{ route('save-bank-slip', [$appointment->id]) }}" method="POST" enctype="multipart/form-data">
@csrf
    <div class="row justify-content-center">
        <div class="col-md-4 ">
            <div class="card justify-content-center text-center">
                <div class="card-body">
                    <div class="drop-zone">
                        <span class="drop-zone__prompt">
                            <i class="fa fa-cloud-upload fa-3x"  aria-hidden="true"></i>
                            <br/>
                            Drop your document or browse
                        </span>
                        <input type="file" name="bank_slip" class="drop-zone__input">
                    </div>
                    <br/>
                    <button type="submit" class="btn btn-info btn-lg">Upload</button>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection
@section("scripts")
<script>
document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
  const dropZoneElement = inputElement.closest(".drop-zone");

  dropZoneElement.addEventListener("click", (e) => {
    inputElement.click();
  });

  inputElement.addEventListener("change", (e) => {
    if (inputElement.files.length) {
      updateThumbnail(dropZoneElement, inputElement.files[0]);
    }
  });

  dropZoneElement.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZoneElement.classList.add("drop-zone--over");
  });

  ["dragleave", "dragend"].forEach((type) => {
    dropZoneElement.addEventListener(type, (e) => {
      dropZoneElement.classList.remove("drop-zone--over");
    });
  });

  dropZoneElement.addEventListener("drop", (e) => {
    e.preventDefault();

    if (e.dataTransfer.files.length) {
      inputElement.files = e.dataTransfer.files;
      updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
    }

    dropZoneElement.classList.remove("drop-zone--over");
  });
});

/**
 * Updates the thumbnail on a drop zone element.
 *
 * @param {HTMLElement} dropZoneElement
 * @param {File} file
 */
function updateThumbnail(dropZoneElement, file) {
  let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

  // First time - remove the prompt
  if (dropZoneElement.querySelector(".drop-zone__prompt")) {
    dropZoneElement.querySelector(".drop-zone__prompt").remove();
  }

  // First time - there is no thumbnail element, so lets create it
  if (!thumbnailElement) {
    thumbnailElement = document.createElement("div");
    thumbnailElement.classList.add("drop-zone__thumb");
    dropZoneElement.appendChild(thumbnailElement);
  }

  thumbnailElement.dataset.label = file.name;
}

</script>
@endsection