@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTablepayment = $('#payment-table'),
                dt_permission;

            if (dataTablepayment.length) {
                dt_permission = dataTablepayment.DataTable({
                    ajax: "{{ route('payment') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            title: 'No.'
                        },
                        {
                            data: 'student.name',
                            title: 'Student Name'
                        },
                        {
                            data: 'course.name',
                            title: 'Course Name'
                        },
                        // {
                        //     data: 'transaction_id',
                        //     title: 'Transaction ID'
                        // },
                        {
                            data: 'transaction_id',
                            title: 'Transaction ID',
                            render: function(data, type, full, meta) {
                                return `<span style="cursor:pointer; color:blue;" onclick="generateReceipt('${data}')">${data}</span>`;
                            }
                        },
                        {
                            data: 'amount',
                            title: 'Amount',
                            render: function(data, type, full, meta) {
                                if (!data) return 'Rs 0';
                                return 'Rs ' + parseFloat(data).toLocaleString('en-IN');
                            }
                        },


                        {
                            data: 'payment_status',
                            title: 'Payment Status'
                        },
                        {
                            data: 'payment_confirmation_date',
                            title: 'Confirmation Date'
                        },
                        // {
                        //     data: '',
                        //     title: 'Actions'
                        // },
                    ],
                    columnDefs: [{
                            targets: 5,
                            render: function(data, type, full, meta) {
                                let statusLabel = '';
                                let badgeClass = '';

                                switch (full['payment_status']) {
                                    case 'pending':
                                        statusLabel = 'Pending';
                                        badgeClass = 'badge bg-warning';
                                        break;
                                    case 'completed':
                                        statusLabel = 'Completed';
                                        badgeClass = 'badge bg-success';
                                        break;
                                    case 'failed':
                                        statusLabel = 'Failed';
                                        badgeClass = 'badge bg-danger';
                                        break;
                                    default:
                                        statusLabel = 'Unknown';
                                        badgeClass = 'badge bg-secondary';
                                }

                                return `<span class="${badgeClass}">${statusLabel}</span>`;
                            }
                        },
                        {
                            targets: 6,
                            render: function(data, type, full, meta) {
                                return data ? moment(data).format('DD-MM-YYYY HH:mm A') : 'N/A';
                            }
                        },
                        // {
                        //     targets: -1,
                        //     searchable: false,
                        //     orderable: false,
                        //     render: function(data, type, full, meta) {
                        //         return (
                        //             '<span class="text-nowrap">' +
                        //             '<button class="btn btn-sm btn-icon me-2" onclick="edit(\'/payment/edit/' +
                        //             full['id'] + '\', \'modal-lg\')">' +
                        //             '<i class="ti ti-edit"></i></button>' +
                        //             '<button class="btn btn-sm btn-icon delete-record" onclick="destry(\'/payment/destroy/' +
                        //             full['id'] + '\', \'payment-table\')">' +
                        //             '<i class="ti ti-trash"></i></button></span>'
                        //         );
                        //     }
                        // }
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
                        text: 'Add Payment',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('payment.create') }}', 'modal-lg')"
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
                                    return 'Details of ' + data['student.name'];
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== '' ? '<tr data-dt-row="' + col
                                        .rowIndex +
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

    <script type="text/javascript">
        window.generateReceipt = function(transactionId) {
            $.ajax({
                url: "{{ route('generate.fee.receipt') }}",
                method: "POST",
                data: {
                    txnid: transactionId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);

                    if (response.status === 'success') {
                        window.open(response.pdf_url, '_blank');
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error generating receipt. Please try again.');
                    console.error(xhr.responseText);
                }
            });
        };
    </script>


    <h4 class="mb-4">Payment List</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="payment-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Student Name</th>
                        <th>Course Name</th>
                        <th>Amount</th>
                        <th>Transaction ID</th>
                        <th>Payment Status</th>
                        <th>Confirmation Date</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
