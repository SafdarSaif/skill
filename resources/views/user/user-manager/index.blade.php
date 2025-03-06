@extends('layouts.main')

@section('content')

  <script type="module">
    $(function() {
      var dataTablePermissions = $('#user-manager-table'),
        dt_permission;
      // Users List datatable
      if (dataTablePermissions.length) {
        dt_permission = dataTablePermissions.DataTable({
          ajax: "{{ route('user-manager') }}",
          columns: [{
              data: ''
            },
            {
              data: 'name'
            },
            {
              data: 'email'
            },
            {
              data: 'created_at'
            },
            {
              data: ''
            },
          ],
          columnDefs: [{
              // For Responsive
              className: 'control',
              orderable: false,
              searchable: false,
              responsivePriority: 2,
              targets: 0,
              render: function(data, type, full, meta) {
                return '';
              }
            },
            {
              // Name
              targets: 1,
              render: function(data, type, full, meta) {
                var $name = full['name'];
                return '<span class="text-nowrap">' + $name + '</span>';
              }
            },
            {
              // User Role
              targets: 2,
              orderable: false,
              render: function(data, type, full, meta) {
                var $assignedTo = full['roles'],
                  $output = '';
                for (var i = 0; i < $assignedTo.length; i++) {
                  $output += '<span class="badge bg-label-primary m-1">' + $assignedTo[i] + '</span></a>';
                }
                return '<span class="text-nowrap">' + $output + '</span>';
              }
            },
            {
              targets: 3,
              orderable: false,
              render: function(data, type, full, meta) {
                var $date = full['created_at'];
                return '<span class="text-nowrap">' + $date + '</span>';
              }
            },
            {
              // Actions
              targets: -1,
              searchable: false,
              title: 'Actions',
              orderable: false,
              render: function(data, type, full, meta) {
                return (
                  '<span class="text-nowrap"><button class="btn btn-sm btn-icon me-2" onclick="edit(&#39;users/permissions&#39;, &#39;' + data + '&#39, &#39;modal-md&#39;)"><i class="ti ti-edit"></i></button>' +
                  '<button class="btn btn-sm btn-icon delete-record"><i class="ti ti-trash"></i></button></span>'
                );
              }
            }
          ],
          aaSorting: false,
          dom: '<"row mx-1"' +
            '<"col-sm-12 col-md-3" l>' +
            '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"<"me-3"f>B>>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
          language: {
            sLengthMenu: 'Show _MENU_',
            search: 'Search',
            searchPlaceholder: 'Search..'
          },
          buttons: [{
            text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i> User Manager',
            className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
            attr: {
              'onclick': "add('{{ route('user-manager.create') }}', 'modal-lg')"
            },
            init: function(api, node, config) {
              $(node).removeClass('btn-secondary');
            }
          }],
          // For responsive popup
          responsive: {
            details: {
              display: $.fn.dataTable.Responsive.display.modal({
                header: function(row) {
                  var data = row.data();
                  return 'Details of ' + data['name'];
                }
              }),
              type: 'column',
              renderer: function(api, rowIdx, columns) {
                var data = $.map(columns, function(col, i) {
                  return col.title !==
                    '' // ? Do not show row in modal popup if title is blank (for check box)
                    ?
                    '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>' :
                    '';
                }).join('');

                return data ? $('<table class="table"/><tbody />').append(data) : false;
              }
            }
          }
        });
      }
    });
  </script>
  <h4 class="mb-4">User Managers</h4>

  <!-- Permission Table -->
  <div class="card">
    <div class="card-datatable table-responsive">

      <table id="user-manager-table" class="table border-top">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Assigned To</th>
            <th>Created Date</th>
            <th></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <!--/ Permission Table -->
@endsection
