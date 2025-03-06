@extends('layouts.main')
@section('content')
    <script type="module">
        $(function() {
            var dataTablevertical = $('#vertical-table'),
                dt_permission;
            // Users List datatable
            if (dataTablevertical.length) {
                dt_permission = dataTablevertical.DataTable({
                    ajax: "",
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'created_at'
                        },
                        {
                            data: ''
                        },
                    ],
                    columnDefs: [{
                            targets: 0,
                            render: function(data, type, full, meta) {
                                return data;
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
                            // Name
                            targets: 2,
                            render: function(data, type, full, meta) {
                                var $checkedStatus = full['status'] == 1 ? 'checked' : '';
                                var $nameStatus = full['status'] == 1 ? 'Yes' : 'No';
                                var isDisabled =
                                    'onclick="updateActiveStatus(&#39;/setting/vertical/status/' +
                                    full['id'] + '&#39;, &#39;vertical-table&#39;)"';
                                return '<label class="switch">' +
                                    '<input  type="checkbox" ' + isDisabled + $checkedStatus +
                                    ' class="switch-input">' +
                                    '<span class="switch-toggle-slider">' +
                                    '<span class="switch-on">' +
                                    '<i class="ti ti-check"></i>' +
                                    '</span>' +
                                    '<span class="switch-off">' +
                                    '<i class="ti ti-x"></i>' +
                                    '</span>' +
                                    '</span>' +
                                    '<span class="switch-label">' + $nameStatus + '</span>' +
                                    '</label>';
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
                                    '<span class="text-nowrap"><button class="btn btn-sm btn-icon me-2" onclick="edit(&#39;/setting/vertical/edit/' +
                                    full['id'] +
                                    '&#39; , &#39;modal-md&#39;)"><i class="ti ti-edit"></i></button>' +
                                    '<button class="btn btn-sm btn-icon delete-record"onclick="destry(&#39;/setting/vertical/destroy/' +
                                    full['id'] +
                                    '&#39; , &#39;vertical-table&#39;)"><i class="ti ti-trash"></i></button></span>'
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
                        text: 'Add Vertical',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('', 'modal-md')"
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
                                        '' ? '<tr data-dt-row="' + col.rowIndex +
                                        '" data-dt-column="' + col.columnIndex + '">' +
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
    <h4 class="mb-4">Vertical List</h4>

    <!-- Admission Table -->
    <div class="card">
        <div class="card-datatable table-responsive">

            <table id="vertical-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ Admission Table -->
@endsection
