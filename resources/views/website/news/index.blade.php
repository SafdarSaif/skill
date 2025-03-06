@extends('layouts.main')

@section('content')
    <script type="module">
        $(function() {
            var dataTableNews = $('#news-updates-table'),
                dt_news;

            if (dataTableNews.length) {
                dt_news = dataTableNews.DataTable({
                    ajax: "{{ route('news') }}",
                    columns: [
                        { data: 'DT_RowIndex', title: 'No.' },
                        { data: 'name', title: 'Title' },
                        { data: 'content', title: 'Content' },
                        { data: 'image', title: 'Image' },
                        { data: 'status', title: 'Status' },
                        { data: '', title: 'Actions' }
                    ],
                    columnDefs: [
                        {
                            targets: 3, // Image column
                            render: function(data, type, full, meta) {
                                return data ? `<img src="${data}" alt="News Image" width="50" height="50">` : 'No Image';
                            }
                        },
                        {
                            targets: 4,
                            render: function(data, type, full, meta) {
                                var $checkedStatus = full['status'] == 1 ? 'checked' : '';
                                var $nameStatus = full['status'] == 1 ? 'Published' : 'Draft';
                                var isDisabled =
                                    'onclick="updateActiveStatus(&#39;/news/status/' +
                                    full['id'] + '&#39;, &#39;news-updates-table&#39;)"';
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
                            targets: -1, // Actions column
                            searchable: false,
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<span class="text-nowrap">' +
                                    '<button class="btn btn-sm btn-icon me-2" onclick="edit(\'/news/edit/' +
                                    full['id'] + '\', \'modal-lg\')">' +
                                    '<i class="ti ti-edit"></i></button>' +
                                    '<button class="btn btn-sm btn-icon delete-record" onclick="destroy(\'/news/destroy/' +
                                    full['id'] + '\', \'news-updates-table\')">' +
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
                        searchPlaceholder: 'Search News..'
                    },
                    buttons: [{
                        text: 'Add News',
                        className: 'add-new btn btn-primary mb-3 mb-md-0 waves-effect waves-light',
                        attr: {
                            'onclick': "add('{{ route('news.create') }}', 'modal-lg')"
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
                                    return 'Details of ' + data['title'];
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== '' ? '<tr data-dt-row="' + col.rowIndex +
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

    <h4 class="mb-4">News Updates</h4>
    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="news-updates-table" class="table border-top">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
