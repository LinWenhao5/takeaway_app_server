@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Media Library</h1>
    <form action="{{ route('web.media.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Upload Image</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <hr>
    
    <media-library></media-library>
</div>
@endsection

@vite('resources/js/media-library.js')