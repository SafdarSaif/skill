<div class="modal-header">
  <h5 class="modal-title">Reporting for {{ $user->name }}</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="userAddForm" action="{{ route('users.reporting.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
  <div class="modal-body">
    <div class="row g-2">
      <div class="row mb-0">
        <div class="col-md-4">
          <h6>Vertical</h6>
        </div>
        <div class="col-md-8">
          <h6>Reporting</h6>
        </div>
      </div>
      @foreach ($verticals as $vertical)
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-4">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" onchange="getUsers(this.value)" {{ array_key_exists($vertical->id, $reportings) ? 'checked' : '' }} id="vertical_{{ $vertical->id }}" name="vertical_ids[]" value="{{ $vertical->id }}">
                <label class="form-check-label" for="vertical_{{ $vertical->id }}">{{ $vertical->short_name . ' (' . $vertical->vertical_name . ')' }}</label>
              </div>
            </div>
            <div class="col-md-8">
              <select class="form-select" id="user_{{ $vertical->id }}" name="user_ids[{{ $vertical->id }}]">
                <option value="">Choose</option>
              </select>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
  </div>
</form>

<script>
  var reportings = JSON.parse('{!! json_encode($reportings) !!}');

  $(function() {
    $("#userAddForm").validate();
    $("#userAddForm").submit(function(e) {
      e.preventDefault();
      if ($("#userAddForm").valid()) {
        $(':input[type="submit"]').prop('disabled', true);
        var formData = new FormData(this);
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("user_id", "{{ $user->id }}");
        $.ajax({
          url: $(this).attr('action'),
          type: $(this).attr('method'),
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function(response) {
            $(':input[type="submit"]').prop('disabled', false);
            if (response.status == 'success') {
              toastr.success(response.message);
              $(".modal").modal('hide');
              $('#users-table').DataTable().ajax.reload();
            } else {
              toastr.error(response.message);
            }
          },
          error: function(response) {
            $(':input[type="submit"]').prop('disabled', false);
            toastr.error(response.responseJSON.message);
          }
        });
      }
    })
  })

  function getUsers(verticalId, parentId = 0) {
    if ($("#vertical_" + verticalId).prop("checked") == true) {
      const userId = '{{ $user->id }}';
      $.ajax({
        url: '/users/by-vertical/' + verticalId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.status == 'success') {
            $.each(response.data, function(key, value) {
              if (value.user_id != parseInt(userId)) {
                $('#user_' + verticalId).append('<option value="' + value.user_id + '">' + value.user.name + ' (' + value.user.email + ')</option>');
              }
            })

            $("#user_" + verticalId).select2({
              placeholder: 'Choose',
              dropdownParent: $('#modal-lg')
            });

            if(parentId>0){
              $("#user_" + verticalId).val(parentId).trigger('change');
            }

            $("#user_" + verticalId).rules('add', {
              required: true
            });
          } else {
            toastr.error(response.message);
          }
        }
      })
    } else {
      $("#user_" + verticalId).html('');
      $("#user_" + verticalId).rules('remove');
    }
  }

  $.each(reportings, function(verticalId, parentId){
    getUsers(verticalId, parentId);
  })
</script>
