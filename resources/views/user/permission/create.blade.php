<div class="modal-body">
  <div class="text-center mb-4">
    <h3 class="mb-2">Add New Permission</h3>
    <p class="text-muted">Permissions you may use and assign to your users.</p>
  </div>
  <form id="addPermissionForm" action="{{ url('users/permissions') }}" method="POST" class="row">
    <div class="col-12 mb-3">
      <label class="form-label" for="name">Permission Name</label>
      <input type="text" id="name" name="name" class="form-control" placeholder="Permission Name" autofocus />
    </div>
    <div class="col-12 text-center demo-vertical-spacing">
      <button type="submit" class="btn btn-primary me-sm-3 me-1">Create Permission</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
    </div>
  </form>
</div>

<script>
  $("#addPermissionForm").validate({
    rules: {
      name: {
        required: true
      }
    },
    messages: {
      name: {
        required: "Please enter permission name"
      }
    }
  });

  $("#addPermissionForm").submit(function (e) {
    e.preventDefault();
    if ($("#addPermissionForm").valid()) {
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
          if (response.status == 'success') {
            toastr.success(response.message);
            $(".modal").modal('hide');
            $('#permissions-table').DataTable().ajax.reload();
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
