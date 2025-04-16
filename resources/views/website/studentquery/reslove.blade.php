<div class="modal-body position-relative">
    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

    <style>
        .modal-body h3 {
            font-weight: 700;
        }

        .card {
            border-radius: 1rem;
            transition: 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 123, 255, 0.15);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
            border-color: #dee2e6;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #0d6efd;
        }

        .form-section {
            margin-bottom: 1.25rem;
        }

        .list-unstyled a {
            text-decoration: none;
            color: #0d6efd;
        }

        .list-unstyled a:hover {
            text-decoration: underline;
        }

        .btn-success {
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
        }

        .btn-secondary {
            border-radius: 0.5rem;
        }

        .attachment-link {
            display: inline-block;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
    </style>

    <div class="text-center mb-4">
        <h3 class="text-primary">🎓 Solve Student Doubts</h3>
        <p class="text-muted">Review and respond to student-submitted queries below.</p>
    </div>

    @if ($student)
        <div class="text-center mb-4">
            <h5 class="text-primary">👤 <strong>{{ $student->name }}</strong></h5>
            <p class="text-muted">
                📞 {{ $student->mobile ?? 'N/A' }} <br>
                📧 {{ $student->email ?? 'N/A' }}
            </p>
        </div>
    @endif

    <div class="row g-4">
        @foreach ($allQueries as $query)
            @php
                $attachments = $query->attachment ? json_decode($query->attachment, true) : [];
            @endphp

            <!-- Student Query -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Query ID: #{{ $query->id }}
                            <span class="float-end text-muted small">{{ $query->created_at->format('d M Y') }}</span>
                        </h5>
                        <hr>
                        <div class="form-section">
                            <label class="form-label">Student Query</label>
                            <textarea class="form-control" rows="4" readonly>{{ $query->query }}</textarea>
                        </div>

                        @if (!empty($attachments['question']))
                            <div class="form-section">
                                <label class="form-label">Question Attachments</label>
                                <ul class="list-unstyled">
                                    @foreach ($attachments['question'] as $filePath)
                                        <li>
                                            <a href="{{ asset($filePath) }}" target="_blank" class="attachment-link">
                                                📎 {{ basename($filePath) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Answer Form -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <form id="student-query-form-{{ $query->id }}" action="{{ route('studentquery.update', $query->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="form-section">
                                <label class="form-label">Your Answer</label>
                                <textarea name="answer" class="form-control" rows="4" placeholder="Type your response here...">{{ $query->answer }}</textarea>
                            </div>

                            <div class="form-section">
                                <label class="form-label">Upload Answer Attachments</label>
                                <input type="file" name="attachment[]" class="form-control" multiple>
                            </div>

                            @if (!empty($attachments['answer']))
                                <div class="form-section">
                                    <label class="form-label">Answer Attachments</label>
                                    <ul class="list-unstyled">
                                        @foreach ($attachments['answer'] as $filePath)
                                            <li>
                                                <a href="{{ asset($filePath) }}" target="_blank" class="attachment-link">
                                                    📎 {{ basename($filePath) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="text-end">
                                <button type="button" class="btn btn-success submit-answer-btn" data-id="{{ $query->id }}">✅ Submit Answer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal-footer justify-content-end">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

<script>
    $(function () {
        $('.submit-answer-btn').on('click', function () {
            const id = $(this).data('id');
            $(`#student-query-form-${id}`).submit();
        });

        @foreach ($allQueries as $query)
            $(`#student-query-form-{{ $query->id }}`).validate({
                rules: {
                    answer: {
                        required: true,
                        minlength: 5
                    }
                },
                messages: {
                    answer: {
                        required: "Please provide an answer",
                        minlength: "Answer should be at least 5 characters"
                    }
                },
                submitHandler: function (form) {
                    const $btn = $(form).find('.submit-answer-btn');
                    $btn.prop('disabled', true);

                    const formData = new FormData(form);
                    $.ajax({
                        url: $(form).attr('action'),
                        type: $(form).attr('method'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (res) {
                            $btn.prop('disabled', false);
                            if (res.status === 'success' || res.status === true) {
                                toastr.success(res.message || "Answer submitted successfully!");
                                $('.modal').modal('hide');
                                $('#student-query-table').DataTable().ajax.reload();
                            } else {
                                toastr.error(res.message || "Something went wrong.");
                            }
                        },
                        error: function (xhr) {
                            $btn.prop('disabled', false);
                            toastr.error(xhr.responseJSON?.message || "Submission failed!");
                        }
                    });
                }
            });
        @endforeach
    });
</script>
