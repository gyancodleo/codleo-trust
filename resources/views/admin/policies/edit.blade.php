@extends('admin.layouts.main')

@section('main-content')

<div class="dvanimation animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="basic">
        <div class="panel flex items-center overflow-x-auto whitespace-nowrap p-3 justify-between">
            <h1 class="text-xl font-bold mb-4">Edit Policy</h1>
            <a class="btn btn-outline-primary" href="{{ route('admin.policy') }}">Back</a>
        </div>
        <div class="panel mt-6">
            <h5 class="text-lg font-semibold dark:text-white-light">Edit Policy</h5>
            <div class="addpolicycontainer">
                <form action="{{ route('admin.policies.update',$policy->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-semibold">Title</label>
                        <input type="text" name="title" value="{{ $policy->title }}" class="w-full border p-2 rounded" required>
                        @error('title')
                        <span class="text-danger mt-1 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Category</label>
                        <select name="category_id" class="w-full border p-2 rounded" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}" @if($policy->category_id==$c->id) selected @endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="text-danger mt-1 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Description</label>
                        <textarea name="description" class="w-full border p-2 rounded" rows="4" required>{{ $policy->description }}</textarea>
                        @error('description')
                        <span class="text-danger mt-1 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Upload New PDF</label>
                        <input type="file" name="file" accept="application/pdf" class="border p-2 rounded w-full">
                        @if($policy->file_path)
                        <p class="text-sm mt-1">Current file: <strong>{{ $policy->file_path }}</strong></p>
                        @endif
                        @error('file')
                        <span class="text-danger mt-1 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 flex items-center gap-2">
                        <input type="checkbox" name="is_published" value="1" @if($policy->is_published) checked @endif>
                        <label>Published</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <!-- end main content section -->
</div>

@endsection