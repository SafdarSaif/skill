<div class="modal-body">
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="float: right;"></button>
  <div class="text-center mb-6">
    <h4 class="mb-2">Add Counsellor</h4>
  </div>
  <form id="addCounsellorManager" class="row g-6" action="{{ route('counsellors.store') }}" method="post">
  <div class="col-md-6">
    <label class="form-label" for="user_type">User Type</label>
    <select class="form-select form-control" id="user_type" name="user_type">
      <option value="">Choose</option>
      <option value="1">Outsourced</option>
      <option value="0">Inhouse</option>
    </select>
  </div>
    <div class="col-12 col-md-6">
      <label class="form-label" for="name">Name<span class="text-danger">*</span></label>
      <input type="text" id="name" name="name" class="form-control" placeholder="ex: Jhon Doe" />
    </div>
    <div class="col-12 col-md-6">
      <label class="form-label" for="code">Employee ID<span class="text-danger">*</span></label>
      <input type="text" id="code" name="code" class="form-control" placeholder="ex: EM0001" />
    </div>
    <div class="col-6 col-md-6">
      <label class="form-label" for="email">Email<span class="text-danger">*</span></label>
      <input type="text" id="email" name="email" class="form-control" placeholder="ex: user@example.com" />
    </div>
    <div class="col-12 col-md-6">
      <label class="form-label" for="contact">Contact<span class="text-danger">*</span></label>
      <input type="tel" id="contact" name="contact" class="form-control phone-mask" placeholder="ex: 9998777655"
        minlength="10" maxlength="10" onkeypress="return isNumberKey(event)" />
    </div>
<!-- <div class="col-12 col-md-6">
      <label class="form-label" for="photo">Photo </label>
      <input type="file" name="photo" class="form-control"  accept="image/png, image/jpg, image/jpeg, image/svg">
    </div> -->
    <div class="col-md-6">
        <div class="row mb-2">
          <div class="col-md-9">
            <label class="form-label" for="photo">Photo</label>
            <input type="file" id="photo" name="photo" class="form-control" accept="image/png, image/jpg, image/jpeg, image/svg" onchange="document.getElementById('avatarImage').src = window.URL.createObjectURL(this.files[0]); $('#avatarImage').css('display', 'flex'); $('#nameInitials').css('display', 'none')" accept="image/*">
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <div class="avatar me-2">
              <span id="nameInitials" class="avatar-initial rounded-circle bg-label-success">UN</span>
              <img id="avatarImage" src="" alt="" class="rounded" style="display: none">
            </div>
          </div>
        </div>
      </div>
    <div class="col-12 text-center">
      <button type="submit" class="btn btn-primary me-3">Submit</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
    </div>
  </form>
</div>


<script>
  $("#addCounsellorManager").validate({
    rules: {
      name: {
        required: true
      },
      code: {
        required: true
      },
      email: {
        required: true
      },
      contact: {
        required: true
      },
    },
    messages: {
      name: {
        required: "This field is required."
      }
    }
  });

  $("#addCounsellorManager").submit(function (e) {
    e.preventDefault();
    if ($("#addCounsellorManager").valid()) {
      $(':input[type="submit"]').prop('disabled', true);
      var formData = new FormData(this);
      formData.append("_token", "{{ csrf_token() }}");
      $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
          $(':input[type="submit"]').prop('disabled', false);
          if (response.status == '200') {
            toastr.success(response.message);
            $(".modal").modal('hide');
            $('#users-table').DataTable().ajax.reload();
          } else {
            toastr.error(response.message);
          }
        },
        error: function (response) {
          $(':input[type="submit"]').prop('disabled', false);
          toastr.error(response.responseJSON.message);
        }
      });
    }
  })

</script>