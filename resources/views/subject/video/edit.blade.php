<div class="modal-body">
    <div class="text-center mb-3">
        <h3 class="mb-2 text-primary">Edit Subject Video</h3>
        <p class="text-muted">Update the subject video details below</p>
    </div>

    <form id="subject-video-form" action="{{ route('subjectvideo.update', $video->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf
       
        <!-- Subject Selection -->
        <div class="col-md-6">
            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subject_id" class="form-select" required>
                <option value="">Select Subject</option>
                @foreach ($subjects as $id => $name)
                    <option value="{{ $id }}" {{ $video->subject_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Video Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">Video Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $video->name }}" required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ $video->description }}</textarea>
        </div>

        <!-- Duration -->
        <div class="col-md-6">
            <label for="duration" class="form-label">Duration (HH:MM:SS)</label>
            <input type="text" name="duration" id="duration" class="form-control" value="{{ $video->duration }}"
                pattern="^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$" placeholder="HH:MM:SS" title="Enter duration in HH:MM:SS format">
        </div>

        <!-- Uploader -->
        <div class="col-md-6">
            <label for="user_id" class="form-label">Uploader <span class="text-danger">*</span></label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Select Uploader</option>
                @foreach ($users as $id => $name)
                    <option value="{{ $id }}" {{ $video->user_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Position -->
        <div class="col-md-6">
            <label for="position" class="form-label">Position</label>
            <select name="position" id="position" class="form-select">
                <option value="0" {{ $video->position == 0 ? 'selected' : '' }}>General Videos (0)</option>
                <option value="1" {{ $video->position == 1 ? 'selected' : '' }}>Last Videos (1)</option>
            </select>
        </div>

        <!-- Upload Type -->
        <div class="col-md-6">
            <label for="upload_type" class="form-label">Upload Type <span class="text-danger">*</span></label>
            <select name="upload_type" id="upload_type" class="form-select" required>
                <option value="youtube" {{ $video->upload_type == 'youtube' ? 'selected' : '' }}>YouTube</option>
                <option value="local" {{ $video->upload_type == 'local' ? 'selected' : '' }}>Local</option>
            </select>
        </div>

        <!-- YouTube Video URL -->
        <div class="col-md-12" id="youtube_field" style="display: {{ $video->upload_type == 'youtube' ? 'block' : 'none' }};">
            <label for="video_url" class="form-label">YouTube Video URL <span class="text-danger">*</span></label>
            <input type="url" name="video_url" id="video_url" class="form-control" value="{{ $video->video_url }}"
                placeholder="Enter YouTube embedded URL">
            <small class="text-muted">⚠ Only YouTube embedded URLs are allowed (e.g.,
                <code>https://www.youtube.com/embed/VIDEO_ID</code>).</small>
        </div>

        <!-- Local File Upload -->
        <div class="col-md-12" id="local_field" style="display: {{ $video->upload_type == 'local' ? 'block' : 'none' }};">
            <label for="video_file" class="form-label">Upload Video File <span class="text-danger">*</span></label>
            <input type="file" name="video_file" id="video_file" class="form-control" accept="video/*">
        </div>

        <!-- Submit Buttons -->
        <div class="col-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Update Video</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>
