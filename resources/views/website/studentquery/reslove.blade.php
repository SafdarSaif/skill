<div class="modal-body position-relative">
    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
        aria-label="Close"></button>

    <style>
        .modal-body h3 {
            font-weight: 600;
        }

        .card {
            border-radius: 12px;
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 123, 255, 0.1);
        }

        .form-label {
            font-size: 0.95rem;
            font-weight: 500;
            color: #333;
        }

        textarea.form-control[readonly],
        input.form-control[readonly] {
            background-color: #f5f5f5;
            border-color: #ddd;
            cursor: not-allowed;
        }

        .list-unstyled a {
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .list-unstyled a:hover {
            color: #0056b3;
        }

        .btn-success {
            font-weight: 500;
            padding: 0.5rem 1.5rem;
        }

        .btn-secondary {
            padding: 0.4rem 1.5rem;
        }

        .card-body label {
            margin-bottom: 0.25rem;
        }

        .text-muted.small {
            font-size: 0.8rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0056b3;
            margin-bottom: 1rem;
        }

        .form-section {
            margin-bottom: 1.5rem;
        }

        .select-disabled {
            background-color: #f9f9f9;
            border-color: #ddd;
            cursor: not-allowed;
        }
    </style>

    <div class="text-center mb-4">
        <h3 class="mb-2 text-primary">🎓 Solve Student Doubts</h3>
        <p class="text-muted">Review and respond to student-submitted queries below.</p>
    </div>

    <div class="row g-4">
        @foreach ($allQueries as $query)
            @php
                $attachments = $query->attachment ? json_decode($query->attachment, true) : [];
            @endphp

            <!-- Query Details -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="text-dark mb-3">
                            Query ID: #{{ $query->id }}
                            <span class="float-end text-muted small">
                                {{ $query->created_at->format('d M Y, h:i A') }}
                            </span>
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
                                            <a href="{{ asset($filePath) }}" target="_blank" class="text-primary">
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

            <!-- Answer Section -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <form id="edit-student-query-form-{{ $query->id }}"
                            action="{{ route('studentquery.update', $query->id) }}" method="POST"
                            enctype="multipart/form-data">
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
                                                <a href="{{ asset($filePath) }}" target="_blank" class="text-primary">
                                                    📎 {{ basename($filePath) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="text-end">
                                {{-- <button type="submit" class="btn btn-success px-4">✅ Submit Answer</button> --}}
                                <button type="button" class="btn btn-success px-4 submit-answer-btn"
                                    data-id="{{ $query->id }}">✅ Submit Answer</button>

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
    $(document).ready(function () {
        @foreach ($allQueries as $query)
            $("#edit-student-query-form-{{ $query->id }}").validate({
                rules: {
                    answer: {
                        required: true,
                        minlength: 5
                    }
                },
                messages: {
                    answer: {
                        required: "Please write an answer",
                        minlength: "Answer must be at least 5 characters"
                    }
                },
                submitHandler: function(form) {
                    let formId = $(form).attr('id');
                    let submitBtn = $(form).find('button[type="submit"]');
                    submitBtn.prop('disabled', true);
    
                    let formData = new FormData(form);
                    formData.append("_token", "{{ csrf_token() }}");
    
                    $.ajax({
                        url: $(form).attr('action'),
                        type: $(form).attr('method'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            submitBtn.prop('disabled', false);
                            if (response.status === 'success' || response.status === true) {
                                toastr.success(response.message || "Answer submitted successfully!");
                                $(".modal").modal('hide');
                                $('#student-query-table').DataTable().ajax.reload(); // Optional reload
                            } else {
                                toastr.error(response.message || "Something went wrong.");
                            }
                        },
                        error: function(xhr) {
                            submitBtn.prop('disabled', false);
                            toastr.error(xhr.responseJSON?.message || "Submission failed!");
                        }
                    });
                }
            });
        @endforeach
    });
    </script>
    
