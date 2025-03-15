@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTableContact = $('#contact-table'),
                dt_contact;

            if (dataTableContact.length) {
                dt_contact = dataTableContact.DataTable({
                    ajax: "{{ route('contact') }}",
                    columns: [
                        { data: 'DT_RowIndex', title: 'No.' },
                        { data: 'name', title: 'Name' },
                        { data: 'email', title: 'Email' },
                        { data: 'message', title: 'Message' },
                        { data: 'status', title: 'Status' },
                        { data: '', title: 'Actions' }
                    ],
                    columnDefs: [
                        {
                            targets: 4,
                            render: function(data, type, full, meta) {
                                var checked = full['status'] == 1 ? 'checked' : '';
                                var statusText = full['status'] == 1 ? 'Read' : 'Unread';

                                return `
                                    <label class="switch">
                                        <input type="checkbox" ${checked} onclick="updateActiveStatus('/contact/status/${full['id']}', 'contact-table')" class="switch-input">
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ti ti-check"></i></span>
                                            <span class="switch-off"><i class="ti ti-x"></i></span>
                                        </span>
                                        <span class="switch-label">${statusText}</span>
                                    </label>`;
                            }
                        },
                        {
                            targets: -1, // Actions column
                            searchable: false,
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<span class="text-nowrap">' +
                                    // `<button class="btn btn-sm btn-icon me-2" onclick="edit('/contact/edit/${full['id']}', 'modal-lg')">` +
                                    // '<i class="ti ti-edit"></i></button>' +
                                    `<button class="btn btn-sm btn-icon delete-record" onclick="destry('/contact/destroy/${full['id']}', 'contact-table')">` +
                                    '<i class="ti ti-trash"></i></button></span>'
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
                        searchPlaceholder: 'Search Contacts...'
                    },
                   
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
                                    return col.title !== '' ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                                        <td>${col.title}:</td> 
                                        <td>${col.data}</td>
                                    </tr>` : '';
                                }).join('');
                                return data ? $('<table class="table"/><tbody />').append(data) : false;
                            }
                        }
                    }
                });
            }
        });
    </script>

    <h4 class="mb-4">Contact & Support</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="contact-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
