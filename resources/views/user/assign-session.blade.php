@extends('layouts/layoutMaster')

@section('title', 'User | Assign Vertical')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss', 'resources/assets/vendor/libs/pickr/pickr-themes.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js', 'resources/assets/vendor/libs/pickr/pickr.js'])
@endsection

@section('page-script')
  <script type="module">
    $(function() {
      $("#admission_session_id").select2({
        placeholder: "Choose"
      })

      const dateInputField = document.querySelector("#start_date");
      $("#start_date").datepicker({
        todayHighlight: true,
        format: 'dd-mm-yyyy',
        endDate: '1d',
        orientation: isRtl ? 'auto right' : 'auto left'
      });

      new Inputmask("99-99-9999").mask(dateInputField);
    })
  </script>

  <script>
    function getSchemes() {
      const admissionSessionId = $("#admission_session_id").val();
      $.ajax({
        url: "/settings/dropdowns/schemes-by-admission-session/" + admissionSessionId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.status) {
            var schemeDom = '<h5 class="my-3">Scheme(s)</h5>';
            $.each(response.data, function(key, value) {
              schemeDom += '<div class="col-md-12 mb-2">' +
                '<div class="form-check mt-2">' +
                '<input class="form-check-input" type="checkbox" name="scheme_ids[]" onchange="getFeeStructures(this.value)" value="' + value.id + '" id="scheme-check-' + value.id + '">' +
                '<label class="form-check-label" for="scheme-check-' + value.id + '">' +
                value.name +
                '</label>' +
                '</div>' +
                '<div class="row g-1 mt-2 ms-2" id="feeStructureDom' + value.id + '">' +
                '</div>' +
                '</div>';
            })
            $("#schemeDom").html(schemeDom)
          }
        }
      })
    }

    function getFeeStructures(schemeId) {
      if ($("#scheme-check-" + schemeId).prop("checked") == true) {
        $.ajax({
          url: "/settings/dropdowns/fee-structures-by-scheme/" + schemeId,
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            if (response.status) {
              var feeDom = '<h6 class="mt-3 mb-0">Fee Structure(s)</h6>';
              $.each(response.data, function(key, value) {
                if (value.has_sharing || !value.is_constant) {
                  var isRequired = value.has_sharing ? 'required' : '';
                  var isChecked = value.has_sharing ? 'checked onclick="return false;"' : '';
                  feeDom += '<div class="col-md-12 mb-2">' +
                    '<div class="form-check mt-2">' +
                    '<input class="form-check-input" type="checkbox" name="fee_structure_ids[' + schemeId + '][]" onchange="getFeeSharing(' + schemeId + ')" ' + isChecked + ' value="' + value.id + '" id="fee-structure-check-' + schemeId + value.id + '" ' + isRequired + '>' +
                    '<label class="form-check-label" for="fee-structure-check-' + schemeId + value.id + '">' +
                    value.name +
                    '</label>' +
                    '</div>' +
                    '</div>';
                }

              })
              feeDom += '<div class="row g-1 mt-2">' +
                '<h6 class="mt-3 mb-0">Sharing on the basis of</h6>' +
                '<div class=" d-flex align-content-start" id="feeSharingTypeDom' + schemeId + '"></div>' +
                '</div>' +
                '<div class="row g-1 my-4" id="feeSharingDom' + schemeId + '"></div>';
              $("#feeStructureDom" + schemeId).html(feeDom)
              getFeeSharingTypes(schemeId);
            }
          }
        })
      } else {
        $("#feeStructureDom" + schemeId).html("")
      }
    }

    function getFeeSharingTypes(schemeId) {
      const types = ['Durations', 'Programs and Specializations', 'Programs, Specializations and Durations'];
      var typeDom = '';
      $.each(types, function(key, value) {
        typeDom +=
          '<div class="form-check mt-2 me-2">' +
          '<input class="form-check-input" type="checkbox" name="fee_sharing_type[' + schemeId + ']" onchange="getFeeSharing(' + schemeId + ')" value="' + value + '" id="fee-sharing-type-check-' + schemeId + key + '">' +
          '<label class="form-check-label" for="fee-sharing-type-check-' + schemeId + key + '">' +
          value +
          '</label>' +
          '</div>';
      })
      $("#feeSharingTypeDom" + schemeId).html(typeDom);
      getFeeSharing(schemeId);
    }

    function getFeeSharing(schemeId) {
      const feeStructureIds = [],
        feeSharingType = [],
        maxDuration = {{ $maxDuration }};
      verticalSharingOn = JSON.parse('{!! json_encode($metadata) !!}');
      specializations = JSON.parse('{!! json_encode($specializations) !!}');

      $('input[name="fee_structure_ids[' + schemeId + '][]"]:checked').each(function() {
        feeStructureIds.push($(this).val());
      });

      $('input[name="fee_sharing_type[' + schemeId + ']"]:checked').each(function() {
        feeSharingType.push($(this).val());
      });
      var selectedFeeType = feeSharingType.slice(-1).pop(),
        feeStructures = "",
        sharingDom = "",
        theads = "",
        tbody = "";
      $.ajax({
        url: "/settings/dropdowns/fee-structures-by-scheme/" + schemeId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          feeStructures = response.data;
          if (selectedFeeType == undefined) {
            theads += '<th>Fee Structure</th>';
            theads += '<th>Sharing Amount/Percentage</th>';
            $.each(feeStructureIds, function(key, value) {
              var feeStructure = feeStructures.find(feeStructure => feeStructure.id == parseInt(value));
              var maxValue = feeStructure.has_sharing ? "100" : "",
                placeholder = feeStructure.has_sharing ? "Sharing " + verticalSharingOn : "Variable Amount";
              var inputField = '<input type="number" class="form-control border-none" min="0" max="' + maxValue + '" name="fee[' + schemeId + '][' + feeStructure.id + ']" placeholder="' + placeholder + '">'

              tbody += '<tr><td>' + feeStructure.name + '</td><td>' + inputField + '</td></tr>';
            })
          } else if (selectedFeeType == 'Durations') {
            theads += '<th>Duration</th>';
            $.each(feeStructureIds, function(key, value) {
              var feeStructure = feeStructures.find(feeStructure => feeStructure.id == parseInt(value));
              theads += '<th>' + feeStructure.name + '</th>';
            })

            for (var i = 1; i <= maxDuration; i++) {
              tbody += '<tr>' +
                '<td>' + i + '</td>';
              $.each(feeStructureIds, function(key, value) {
                var feeStructure = feeStructures.find(feeStructure => feeStructure.id == parseInt(value));
                var maxValue = feeStructure.has_sharing ? "100" : "",
                  placeholder = feeStructure.has_sharing ? "Sharing " + verticalSharingOn : "Variable Amount";
                var inputField = '<input type="number" class="form-control border-none" min="0" max="' + maxValue + '" name="fee[' + schemeId + '][' + feeStructure.id + '][' + i + ']" placeholder="' + placeholder + '">'

                tbody += '<td>' + inputField + '</td>';
              })
              tbody += '</tr>';
            }


          } else if (selectedFeeType == 'Programs and Specializations') {
            theads += '<th>Programs and Secializations</th>';
            $.each(feeStructureIds, function(key, value) {
              var feeStructure = feeStructures.find(feeStructure => feeStructure.id == parseInt(value));
              theads += '<th>' + feeStructure.name + '</th>';
            })

            $.each(specializations, function(key, value) {
              tbody += '<tr>'
              var specialization = '<div class="d-flex flex-column"><a href="" class="text-body text-truncate"><span class="fw-medium">' +
                value.name +
                '</span></a>' +
                '<small class="text-muted">' +
                value.department + ' | ' + value.program + ' | ' + value.programType + ' | ' + value.minDuration + ' ' + value.mode + '</small>' +
                '</div>';

              tbody += '<td>' + specialization + '</td>';
              $.each(feeStructureIds, function(key, feeStructureId) {
                var feeStructure = feeStructures.find(feeStructure => feeStructure.id == parseInt(feeStructureId));
                var maxValue = feeStructure.has_sharing ? "100" : "",
                  placeholder = feeStructure.has_sharing ? "Sharing " + verticalSharingOn : "Variable Amount";
                var inputField = '<input type="number" class="form-control border-none" min="0" max="' + maxValue + '" name="fee[' + schemeId + '][' + feeStructure.id + '][' + value.id + ']" placeholder="' + placeholder + '">'
                tbody += '<td>' + inputField + '</td>';
              })
              tbody += '</tr>';
            })


          } else if (selectedFeeType == 'Programs, Specializations and Durations') {
            theads += '<th>Programs and Secializations</th>';
            theads += '<th>Durations</th>';
            $.each(feeStructureIds, function(key, value) {
              var feeStructure = feeStructures.find(feeStructure => feeStructure.id == parseInt(value));
              theads += '<th>' + feeStructure.name + '</th>';
            })

            $.each(specializations, function(key, value) {
              for (var i = 1; i <= maxDuration; i++) {
                tbody += '<tr>'
                var specialization = '<div class="d-flex flex-column"><a href="" class="text-body text-truncate"><span class="fw-medium">' +
                  value.name +
                  '</span></a>' +
                  '<small class="text-muted">' +
                  value.department + ' | ' + value.program + ' | ' + value.programType + ' | ' + value.minDuration + ' ' + value.mode + '</small>' +
                  '</div>';

                tbody += '<td>' + specialization + '</td>';
                tbody += '<td>' + i + '</td>';
                $.each(feeStructureIds, function(key, feeStructureId) {
                  var feeStructure = feeStructures.find(feeStructure => feeStructure.id == parseInt(feeStructureId));
                  var maxValue = feeStructure.has_sharing ? "100" : "",
                    placeholder = feeStructure.has_sharing ? "Sharing " + verticalSharingOn : "Variable Amount";
                  var inputField = '<input type="number" class="form-control border-none" min="0" max="' + maxValue + '" name="fee[' + schemeId + '][' + feeStructure.id + '][' + value.id + '][' + i + ']" placeholder="' + placeholder + '">'
                  tbody += '<td>' + inputField + '</td>';
                })
                tbody += '</tr>';
              }
            })
          }

          var message = selectedFeeType !== undefined && selectedFeeType.match('Programs') ? '<small class="text-muted">*Left blank if you don\'t want to assign specialization.</small>' : "";
          var table = '<div class="col-md-12">' +
            message +
            '<div class="table-responsive text-nowrap">' +
            '<table class="table table-bordered">' +
            '<thead>' +
            '<tr>' + theads + '</tr>' +
            '</thead>' +
            '<tbody>' +
            tbody +
            '</tbody>' +
            '</table>' +
            '</div>' +
            '</div>';

          $("#feeSharingDom" + schemeId).html(table);
        }
      })
    }
  </script>

  <script type="module">
    $("#assignSessionForm").validate({
      rules: {
        admission_session_id: {
          required: true
        },
        start_date: {
          required: true
        }
      }
    })
    $("#assignSessionForm").submit(function(e) {
      e.preventDefault();
      if ($("#assignSessionForm").valid()) {
        $(':input[type="submit"]').prop('disabled', true);
        var formData = new FormData(this);
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("user_id", "{{ $user->id }}");
        formData.append("vertical_id", "{{ $vertical->id }}");
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
              setTimeout(() => {
                window.location.href = window.location.href.replace('/create', '');
              }, 2000);
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
  </script>
@endsection

@section('content')

  <div class="row g-4 mb-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2">
      <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1 mt-3">{{ $user->name }}</h4>
        <p class="text-muted">{{ $user->email }} | {{ $vertical->fullName }}</p>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-2">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <form id="assignSessionForm" action="{{ route('users.assign-sessions.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <label class="form-label" for="admission_session_id">Admission Session</label>
                <select class="form-select" id="admission_session_id" name="admission_session_id" onchange="getSchemes()">
                  <option value="">Choose</option>
                  @foreach ($vertical->admissionSessions as $admissionSession)
                    <option value="{{ $admissionSession->id }}">{{ $admissionSession->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="start_date">Start Date</label>
                <input type="tel" id="start_date" name="start_date" class="form-control" placeholder="ex: DD-MM-YYYY">
              </div>
            </div>

            <div class="row g-2 my-3" id="schemeDom">

            </div>
            <div class="row">
              <div class="col-md-12  d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
