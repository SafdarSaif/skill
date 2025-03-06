<div class="modal-body">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="float: right;"></button>
    <div class="text-center mb-6">
        <h4 class="mb-2">Allot Boards</h4>
    </div>
    <form id="addOperationsManager" class="row g-6" action="{{ route('operations.allot')}}" method="post">
    @foreach ($get_uni_name as $university)
        <div class="col-12  col-md-12">
            <input class="form-check-input" type="checkbox"  name="allot_university[]"   @if (isset($get_alloted_uni_name['university_id']) && $university['id'] == $get_alloted_uni_name['university_id']) checked  @endif value="{{ $university['id'] }}" id="allot-university-{{ $university['id'] }}" >
            <label class="form-check-label custom-option-content" for="{{ $university['id'] }}"> {{ $university['Name'] }}</label>
        </div>
    @endforeach
    <input type="hidden" name="user_id" value="{{$id}}">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-3">Alllot</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        </div>
    </form>
</div>


<script>

    $("#addOperationsManager").submit(function (e) {
        e.preventDefault();
        if ($("#addOperationsManager").valid()) {
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