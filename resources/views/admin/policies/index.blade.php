@extends('admin.layouts.main')

@php
use Illuminate\Support\Str;
@endphp

@section('main-content')

<div class="dvanimation animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="basic">
        <div class="panel items-center overflow-x-auto whitespace-nowrap p-3 flex justify-between mb-4">
            <h1 class="text-xl font-bold">Policies</h1>
            <a href="{{ route('admin.policies.create') }}" class="px-4 py-2 btn btn-outline-primary">Create Policy</a>
        </div>
        <div class="panel mt-6">

            @if(session('success'))
            <div class="mb-4 text-success">{{ session('success') }}</div>
            @endif

            @if(!empty($policies))
            <table class="w-full text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Title</th>
                        <th class="p-2">Category</th>
                        <th class="p-2">Description</th>
                        <th class="p-2">Preview</th>
                        <th class="p-2">Published</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($policies as $policy)
                    <tr class="border-b">
                        <td class="p-2">{{ $policy->title }}</td>
                        <td class="p-2">{{ $policy->category->name ?? '-' }}</td>
                        <td class="p-2">{{ str::limit($policy->description, 60 ?? '-') }}</td>
                        <td class="p-2">
                            <a href="{{ route('admin.policy.preview', $policy->id) }}" class="text-primary">
                                <i class="fa-regular fa-eye text-primary"></i>&nbsp;Preview
                            </a>
                        </td>
                        <td class="p-2">
                            @if($policy->is_published)
                            <span class="text-gray-400">Published</span>
                            @else
                            <span class="text-gray-400">Unpublished</span>
                            @endif
                        </td>
                        <td class="p-2 flex items-center gap-2">
                            <a href="{{ route('admin.policies.edit',$policy->id) }}" class="text-success"><i class="fa-regular fa-pen-to-square text-success"></i></a>

                            <form action="{{ route('admin.policies.destroy',$policy->id) }}" method="POST"
                                onsubmit="return confirm('Delete this policy?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-danger"><i class="fa-solid fa-trash-can text-danger"></i></button>
                            </form>

                            @if(!$policy->is_published)
                            <form action="{{ route('admin.policies.publish',$policy->id) }}" method="POST">
                                @csrf
                                <button class="text-primary">Publish&nbsp;<i class="fa-solid fa-upload text-primary"></i></button>
                            </form>
                            @else
                            <form action="{{ route('admin.policies.unpublish',$policy->id) }}" method="POST">
                                @csrf
                                <button class="text-primary">Unpublish&nbsp;<i class="fa-solid fa-download text-primary"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <h3>No Data Available</h3>
            @endif
            <div class="mt-4">{{ $policies->links() }}</div>
        </div>
    </div>
    <!-- end main content section -->
</div>

@endsection