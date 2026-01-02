@extends('admin.layouts.main')

@section('main-content')

<div class="dvanimation animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="basic">
        <div class="panel flex items-center overflow-x-auto whitespace-nowrap p-3 justify-between">
            <h1 class="text-xl font-bold">Policy Categories</h1>
            <button type="button" onclick="openCreateModal()" class="btn btn-primary">Add Category</button>
        </div>
        <div class="panel mt-6">
            @if(session('success'))
            <div class="text-green-600">{{ session('success') }}</div>
            @endif

            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Name</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($categories as $c)
                    <tr class="border-b">
                        <td class="p-2">{{ $c->name }}</td>
                        <td class="p-2 flex gap-2">
                            <button
                                onclick="openEditModal('{{ $c->id }}', '{{ $c->name }}')"
                                class="text-success">
                                <i class="fa-regular fa-pen-to-square text-success"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.policy.category.delete',$c->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-danger"><i class="fa-solid fa-trash-can text-danger"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- end main content section -->
</div>

<div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" id="categoryModal">

    <div class="flex items-start justify-center min-h-screen px-4" onclick="closeModal(event)">
        <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__slideInDown"
            onclick="event.stopPropagation()">

            <div class="flex bg-[#fbfbfb] items-center justify-between px-5 py-3">
                <h5 class="font-bold text-lg" id="modalTitle">Add Category</h5>

                <button type="button" class="text-gray-600" onclick="hideModal()">
                    ✕
                </button>
            </div>

            <div class="p-5">

                <!-- CREATE + EDIT FORM (same form for both modes) -->
                <form id="categoryForm" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <label class="block text-sm font-medium mb-1">Category Name</label>
                    <input type="text" name="name" id="categoryName"
                        class="w-full border p-2 rounded" required>

                    <div class="flex justify-end mt-6">
                        <button type="button" class="btn btn-danger mr-3" onclick="hideModal()">
                            Discard
                        </button>

                        <button type="submit" class="btn btn-primary ml-3">
                            Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function showModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function hideModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    function closeModal(event) {
        // backdrop click closes modal
        hideModal();
    }

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Add Category';
        document.getElementById('categoryName').value = '';

        // Set form for create
        const form = document.getElementById('categoryForm');
        form.action = "{{ route('admin.policy.category.store') }}";
        document.getElementById('formMethod').value = 'POST';

        showModal();
    }

    function openEditModal(id, name) {
        document.getElementById('modalTitle').innerText = 'Edit Category';
        document.getElementById('categoryName').value = name;

        // Set form for update
        const form = document.getElementById('categoryForm');
        form.action = `/admin/policy/category/update/${id}`;
        document.getElementById('formMethod').value = 'PUT';

        showModal();
    }
</script>
@endsection