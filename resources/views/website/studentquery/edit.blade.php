<div class="modal-body">

    <style>
        .modal-body h3 {
            font-weight: 600;
        }
    
        .card {
            border-radius: 12px;
            transition: all 0.3s ease-in-out;
        }
    
        .card:hover {
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.15);
        }
    
        .form-label {
            font-size: 0.95rem;
            color: #333;
        }
    
        textarea.form-control[readonly],
        input.form-control[readonly] {
            background-color: #f9f9f9;
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
    </style>
    
    <div class="text-center mb-4">
        <h3 class="mb-2 text-primary">Solve Student Doubts</h3>
        <p class="text-muted">Review and manage all submitted queries by this student.</p>
    </div>

    {{-- @foreach ($allQueries as $query)
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <form id="edit-student-query-form-{{ $query->id }}"
                    action="{{ route('studentquery.update', $query->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Header -->
                    <div class="row mb-2">
                        <div class="col-12">
                            <h5 class="text-dark mb-0">
                                Query ID: {{ $query->id }}
                                <span class="float-end text-muted small">{{ $query->created_at->format('d M Y, h:i A') }}</span>
                            </h5>
                            <hr>
                        </div>
                    </div>

                    <!-- Video + Student -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Video</label>
                            <select name="video_id" class="form-select" required>
                                <option value="">Select Video</option>
                                @foreach ($subjectvideo as $id => $name)
                                    <option value="{{ $id }}" {{ $query->video_id == $id ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Student</label>
                            <select name="student_id" class="form-select" required>
                                <option value="">Select Student</option>
                                @foreach ($student as $id => $name)
                                    <option value="{{ $id }}" {{ $query->student_id == $id ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Email + Phone -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" value="{{ $query->email }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" value="{{ $query->phone }}" readonly>
                        </div>
                    </div>

                    <!-- Student Query -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Student Query</label>
                            <textarea class="form-control bg-light" rows="3" readonly>{{ $query->query }}</textarea>
                        </div>
                    </div>

                    <!-- Answer -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Your Answer</label>
                            <textarea name="answer" class="form-control" rows="3" placeholder="Type your response here...">{{ $query->answer }}</textarea>
                        </div>
                    </div>

                    <!-- Upload Attachments -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Upload Answer Attachments</label>
                            <input type="file" name="attachment[]" class="form-control" multiple>
                        </div>
                    </div>

                    <!-- Existing Attachments -->
                    @php
                        $attachments = $query->attachment ? json_decode($query->attachment, true) : [];
                    @endphp

                    <div class="row mb-3">
                        @if (!empty($attachments['question']))
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Question Attachments</label>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($attachments['question'] as $filePath)
                                        <li>
                                            <a href="{{ asset($filePath) }}" target="_blank" class="text-primary">
                                                {{ basename($filePath) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (!empty($attachments['answer']))
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Answer Attachments</label>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($attachments['answer'] as $filePath)
                                        <li>
                                            <a href="{{ asset($filePath) }}" target="_blank" class="text-primary">
                                                {{ basename($filePath) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-success px-4">Submit Answer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach --}}

    <div class="row">
        @foreach ($allQueries as $query)
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="text-dark mb-2">
                            Query ID: {{ $query->id }}
                            <span class="float-end text-muted small">{{ $query->created_at->format('d M Y, h:i A') }}</span>
                        </h5>
                        <hr>
    
                        <!-- Video -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video</label>
                            <select class="form-select" disabled>
                                <option value="">Select Video</option>
                                @foreach ($subjectvideo as $id => $name)
                                    <option value="{{ $id }}" {{ $query->video_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <!-- Student -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Student</label>
                            <select class="form-select" disabled>
                                <option value="">Select Student</option>
                                @foreach ($student as $id => $name)
                                    <option value="{{ $id }}" {{ $query->student_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" value="{{ $query->email }}" readonly>
                        </div>
    
                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" value="{{ $query->phone }}" readonly>
                        </div>
    
                        <!-- Student Query -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Student Query</label>
                            <textarea class="form-control bg-light" rows="4" readonly>{{ $query->query }}</textarea>
                        </div>
    
                        <!-- Question Attachments -->
                        @php
                            $attachments = $query->attachment ? json_decode($query->attachment, true) : [];
                        @endphp
    
                        @if (!empty($attachments['question']))
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Question Attachments</label>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($attachments['question'] as $filePath)
                                        <li>
                                            <a href="{{ asset($filePath) }}" target="_blank" class="text-primary">
                                                {{ basename($filePath) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
    
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm border-0 h-100">
                    <div class="card-body">
                        <form id="edit-student-query-form-{{ $query->id }}"
                              action="{{ route('studentquery.update', $query->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
    
                            <!-- Your Answer -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Your Answer</label>
                                <textarea name="answer" class="form-control" rows="4" placeholder="Type your response here...">{{ $query->answer }}</textarea>
                            </div>
    
                            <!-- Upload Attachments -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload Answer Attachments</label>
                                <input type="file" name="attachment[]" class="form-control" multiple>
                            </div>
    
                            <!-- Answer Attachments -->
                            @if (!empty($attachments['answer']))
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Answer Attachments</label>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($attachments['answer'] as $filePath)
                                            <li>
                                                <a href="{{ asset($filePath) }}" target="_blank" class="text-primary">
                                                    {{ basename($filePath) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
    
                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4">Submit Answer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    

    <!-- Close Button -->
    <div class="text-center">
        <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
    </div>
</div>
