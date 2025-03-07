@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTablefaq = $('#faq-table'),
                dt_faq;

            if (dataTablefaq.length) {
                dt_faq = dataTablefaq.DataTable({
                    ajax: "{{ route('faq') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            title: 'No.'
                        },
                        {
                            data: 'question',
                            title: 'Question'
                        },
                        {
                            data: 'answer',
                            title: 'Answer'
                        },
                        {
                            data: 'status',
                            title: 'Status'
                        },
                        {
                            data: '',
                            title: 'Actions'
                        }
                    ],
                    columnDefs: [{
                            targets: 3,
                            render: function(data, type, full, meta) {
                                var checked = full['status'] == 1 ? 'checked' : '';
                                var statusText = full['status'] == 1 ? 'Published' : 'Draft';

                                return `
                                    <label class="switch">
                                        <input type="checkbox" ${checked} onclick="updateActiveStatus('/faq/status/${full['id']}', 'faq-table')" class="switch-input">
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
                                    '<button class="btn btn-sm btn-icon me-2" onclick="edit(\'/faq/edit/' +
                                    full['id'] + '\', \'modal-lg\')">' +
                                    '<i class="ti ti-edit"></i></button>' +
                                    '<button class="btn btn-sm btn-icon delete-record" onclick="destry(\'/faq/destroy/' +
                                    full['id'] + '\', \'faq-table\')">' +
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
                        searchPlaceholder: 'Search FAQ...'
                    },
                    buttons: [{
                        text: 'Add FAQ',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('faq.create') }}', 'modal-lg')"
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + data['question'];
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

    <h4 class="mb-4">FAQ</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="faq-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
