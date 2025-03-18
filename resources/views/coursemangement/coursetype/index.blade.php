@extends('layouts.main')
@section('content')
    <script type="module">
        $(function() {
            var dataTableCourseType = $('#coursetype-table'),
                dt_permission;
            // Course Type List datatable
            if (dataTableCourseType.length) {
                dt_permission = dataTableCourseType.DataTable({
                    ajax: "{{ route('coursetype') }}",
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'status' },
                        { data: 'is_active_on_home' },
                        { data: '' }
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            render: function(data, type, full, meta) {
                                return data;
                            }
                        },
                        {
                            targets: 1,
                            render: function(data, type, full, meta) {
                                return '<span class="text-nowrap">' + full['name'] + '</span>';
                            }
                        },
                        {
                            targets: 2,
                            render: function(data, type, full, meta) {
                                var checked = full['status'] == 1 ? 'checked' : '';
                                return '<label class="switch">' +
                                    '<input type="checkbox" ' + checked +
                                    ' onclick="updateActiveStatus(\'/coursetype/status/' + full['id'] + '\', \'coursetype-table\')" class="switch-input">' +
                                    '<span class="switch-toggle-slider">' +
                                    '<span class="switch-on"><i class="ti ti-check"></i></span>' +
                                    '<span class="switch-off"><i class="ti ti-x"></i></span>' +
                                    '</span>' +
                                    '<span class="switch-label">' + (full['status'] == 1 ? 'Yes' : 'No') + '</span>' +
                                    '</label>';
                            }
                        },
                        {
                            targets: 3,
                            render: function(data, type, full, meta) {
                                var checked = full['is_active_on_home'] == 1 ? 'checked' : '';
                                return '<label class="switch">' +
                                    '<input type="checkbox" ' + checked +
                                    ' onclick="updateActiveStatus(\'/coursetype/is_active_on_home/' + full['id'] + '\', \'coursetype-table\')" class="switch-input">' +
                                    '<span class="switch-toggle-slider">' +
                                    '<span class="switch-on"><i class="ti ti-check"></i></span>' +
                                    '<span class="switch-off"><i class="ti ti-x"></i></span>' +
                                    '</span>' +
                                    '<span class="switch-label">' + (full['is_active_on_home'] == 1 ? 'Yes' : 'No') + '</span>' +
                                    '</label>';
                            }
                        },
                        {
                            targets: -1,
                            title: 'Actions',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<span class="text-nowrap">' +
                                    '<button class="btn btn-sm btn-icon me-2" onclick="edit(\'/coursetype/edit/' + full['id'] + '\', \'modal-lg\')">' +
                                    '<i class="ti ti-edit"></i></button>' +
                                    '<button class="btn btn-sm btn-icon delete-record" onclick="destry(\'/coursetype/destroy/' + full['id'] + '\', \'coursetype-table\')">' +
                                    '<i class="ti ti-trash"></i></button></span>'
                                );
                            }
                        }
                    ],
                    aaSorting: false,
                    dom: '<"row mx-1"' +
                        '<"col-sm-12 col-md-3" l>' +
                        '<"col-sm-12 col-md-9"' +
                        '<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"' +
                        '<"me-3" f>B>>' +
                        '>t' +
                        '<"row mx-2"' +
                        '<"col-sm-12 col-md-6" i>' +
                        '<"col-sm-12 col-md-6" p>' +
                        '>',
                    language: {
                        sLengthMenu: 'Show _MENU_',
                        search: 'Search',
                        searchPlaceholder: 'Search..'
                    },
                    buttons: [
                        {
                            text: 'Add Course Type',
                            className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                            attr: {
                                'onclick': "add('{{ route('coursetype.create') }}', 'modal-lg')"
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        }
                    ],
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
                                    return col.title !== '' ?
                                        '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                        '<td>' + col.title + ':</td> ' +
                                        '<td>' + col.data + '</td>' +
                                        '</tr>' : '';
                                }).join('');

                                return data ? $('<table class="table"/><tbody />').append(data) : false;
                            }
                        }
                    }
                });
            }
        });
    </script>
    <h4 class="mb-4">Course Type List</h4>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="coursetype-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Is Active On Home Page</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
