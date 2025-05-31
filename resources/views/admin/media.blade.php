@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Media Library</h1>
    <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Upload Image</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <hr>
    
    <div class="row">
        @foreach($media as $item)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ $item->path }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <p class="card-text">{{ $item->name }}</p>
                        <x-delete-confirm
                            :action="route('admin.media.delete', $item->id)"
                            title="Delete Media?"
                            text="Are you sure you want to delete '{{ $item->name }}'?"
                            confirm-button-text="Yes, delete it!"
                            success-message="Media deleted successfully!"
                            error-message="Failed to delete the media."
                            button-class="btn btn-outline-danger btn-sm"
                        >
                            <button type="button" class="btn btn-danger btn-sm">Delete</button>
                        </x-delete-confirm>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

