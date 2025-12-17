@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">
        <h2>✏️ Edit </h2>
        <a href="{{ route('admin.frames.index') }}" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">Editing: **{{ $frame->name }}**</div>
        <div class="card-body">
            <form action="{{ route('admin.frames.update', $frame->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', $frame->name) }}" required>

                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success me-2">Save Changes</button>
                <a href="{{ route('admin.frames.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <h4 class="mt-4 mb-3">🖼️ Frame:</h4>
    <div class="card" style="width: 18rem;">
        <img src="{{ asset('storage/' . $frame->image_path) }}" class="card-img-top" alt="{{ $frame->name }}">
    </div>

</div>
@endsection