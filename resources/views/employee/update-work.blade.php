@extends('employee.layout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Update Work Assignment #{{ $assignment->id }}</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.update-work.post', $assignment) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="in_progress" {{ $assignment->status === 'in_progress' ? 'selected' : '' }}>
                                    In Progress
                                </option>
                                <option value="completed" {{ $assignment->status === 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Update Notes</label>
                            <textarea name="notes" class="form-control" rows="4">{{ $assignment->notes }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Upload Images</label>
                            <input type="file" name="images[]" multiple class="form-control-file">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Work</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection