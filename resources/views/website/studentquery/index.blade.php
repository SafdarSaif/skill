@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTableQuery = $('#student-query-table'),
                dt_query;

            if (dataTableQuery.length) {
                dt_query = dataTableQuery.DataTable({
                    ajax: "{{ route('studentquery') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            title: 'No.'
                        },
                        {
                            data: 'name',
                            title: 'Student Name'
                        },
                        {
                            data: 'email',
                            title: 'Email'
                        },
                        {
                            data: 'query_count',
                            title: 'Total Query'
                        },
                        {
                            data: 'latest_query',
                            title: 'Latest Query',
                            render: function(data, type, full, meta) {
                                if (!data) return '';
                                return data.length > 50 ? data.substring(0, 50) + '...' : data;
                            }
                        },

                        // {
                        //     data: 'attachment',
                        //     title: 'Attachments'
                        // },
                        {
                            data: 'status',
                            title: 'Status'
                        },
                        {
                            data: '',
                            title: 'Actions'
                        }
                    ],
                    columnDefs: [

                        // {
                        //     targets: 4, // Attachments column
                        //     render: function(data, type, full, meta) {
                        //         var attachmentsHtml = '';

                        //         // console.log(full['attachment']); 

                        //         if (full['attachment']) {
                        //             try {
                        //                 // Decode any HTML entities (to convert &quot; back to ")
                        //                 let decodedData = $("<textarea/>").html(full['attachment'])
                        //                     .text();

                        //                 // Parse JSON string to object
                        //                 let attachments = JSON.parse(decodedData);

                        //                 // Display Question Attachments
                        //                 if (attachments['question'] && Object.keys(attachments[
                        //                         'question']).length) {
                        //                     attachmentsHtml += `<strong>Question:</strong><ul>`;
                        //                     Object.values(attachments['question']).forEach(file => {
                        //                         attachmentsHtml +=
                        //                             `<li><a href="${file}" target="_blank">${file.split('/').pop()}</a></li>`;
                        //                     });
                        //                     attachmentsHtml += `</ul>`;
                        //                 }

                        //                 // Display Answer Attachments
                        //                 if (attachments['answer'] && Object.keys(attachments[
                        //                         'answer']).length) {
                        //                     attachmentsHtml += `<strong>Answer:</strong><ul>`;
                        //                     Object.values(attachments['answer']).forEach(file => {
                        //                         attachmentsHtml +=
                        //                             `<li><a href="${file}" target="_blank">${file.split('/').pop()}</a></li>`;
                        //                     });
                        //                     attachmentsHtml += `</ul>`;
                        //                 }

                        //             } catch (e) {
                        //                 console.error("Error parsing attachments:", e);
                        //                 attachmentsHtml = 'Invalid Attachment Data';
                        //             }
                        //         }

                        //         return attachmentsHtml || 'No Attachments';
                        //     }
                        // },


                        {
                            targets: 5,
                            render: function(data, type, full, meta) {
                                var checked = full['status'] == 1 ? 'checked' : '';
                                var statusText = full['status'] == 1 ? 'Resolved' : 'Pending';

                                return `
                                    <label class="switch">
                                        <input type="checkbox" ${checked} onclick="updateActiveStatus('/studentquery/status/${full['id']}', 'student-query-table')" class="switch-input">
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ti ti-check"></i></span>
                                            <span class="switch-off"><i class="ti ti-x"></i></span>
                                        </span>
                                        <span class="switch-label">${statusText}</span>
                                    </label>`;
                            }


                        },
                    //     {
                    //         targets: -1, // Actions column
                    //         searchable: false,
                    //         orderable: false,
                    //         // render: function(data, type, full, meta) {
                    //         //     return `
                    //     //         <span class="text-nowrap">
                    //     //             <button class="btn btn-sm btn-icon me-2" onclick="edit('/studentquery/edit/${full['id']}', 'modal-lg')">
                    //     //                 <i class="ti ti-edit"></i>
                    //     //             </button>
                    //     //             <button class="btn btn-sm btn-icon delete-record" onclick="destry('/studentquery/destroy/${full['id']}', 'student-query-table')">
                    //     //                 <i class="ti ti-trash"></i>
                    //     //             </button>
                    //     //         </span>`;
                    //         // }
                    //         render: function(data, type, full, meta) {
                    //             return `
                    //           <span class="text-nowrap">
                    //            <button class="btn btn-sm btn-icon me-2" onclick="view('/studentquery/show/${full['id']}/${full['student_id']}')">
                    //          <i class="ti ti-eye"></i>
                    //          </button>
                    //   <button class="btn btn-sm btn-icon delete-record" onclick="destry('/studentquery/destroy/${full['id']}', 'student-query-table')">
                    //    <i class="ti ti-trash"></i>
                    //     </button>
                    //      </span>`;
                    //         }

                    //     }

                    {
    targets: -1,
    searchable: false,
    orderable: false,
    render: function(data, type, full, meta) {
        return `
        <span class="text-nowrap">
            <a class="btn btn-sm btn-icon me-2" href="/studentquery/show/${full['id']}/${full['student_id']}">
                <i class="ti ti-eye"></i>
            </a>
            <button class="btn btn-sm btn-icon delete-record" onclick="destry('/studentquery/destroy/${full['id']}', 'student-query-table')">
                <i class="ti ti-trash"></i>
            </button>
        </span>`;
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
                        searchPlaceholder: 'Search Student Queries...'
                    },
                    buttons: [{
                        text: 'Add Query',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('studentquery.create') }}', 'modal-lg')"
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
                                    return 'Details of ' + data['student_name'];
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

    {{-- <script>
        function view(url) {
            window.location.href = url;
        }
    </script> --}}


    <h4 class="mb-4">Student Queries</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="student-query-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Total Query</th>
                        <th>Latest Query</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
