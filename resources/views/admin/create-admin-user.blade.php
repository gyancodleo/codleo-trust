@extends('admin.layouts.main')

@section('main-content')
@php
$authAdmin = auth('admin')->user();
@endphp
<div class="dvanimation animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="basic">
        <div class="panel flex items-center overflow-x-auto whitespace-nowrap p-3 justify-between">
            <h1 class="text-xl font-bold">Admin Users</h1>
            <button type="button" onclick="openCreateModal()" class="btn btn-primary">Create</button>
        </div>
        <div class="panel mt-6">
            @if(session('success'))
            <div class="text-success">{{ session('success') }}</div>
            @endif

            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Name</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Role</th>
                        <th class="p-2">2FA</th>
                        <th class="p-2 w-32">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr class="border-b">
                        <td class="p-2">{{ $admin->name }}</td>
                        <td class="p-2">{{ $admin->email }}</td>
                        <td class="p-2">{{ ucfirst($admin->role) }}</td>
                        <td class="p-2">{{ $admin->is_2fa_enabled ? 'Enabled' : 'Disabled' }}</td>

                        <td class="p-2 flex gap-2">
                            @can('update', $admin)
                            <button onclick="openEditModal({{ $admin }})" class="text-primary"><i class="fa-regular fa-pen-to-square text-success"></i></button>
                            @endcan
                            {{-- DELETE --}}
                            @can('delete', $admin)
                            <form action="{{ route('admin.users.destroy', $admin->id) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this admin user?')">
                                @csrf @method('DELETE')
                                <button class="text-danger"><i class="fa-solid fa-trash-can text-danger"></i></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- end main content section -->
</div>

{{-- ===========================
        MODAL SECTION
   =========================== --}}
<div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" id="adminUserModal">

    <div class="flex items-start justify-center min-h-screen px-4" onclick="hideModal()">

        <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
            onclick="event.stopPropagation()">

            <div class="flex bg-gray-100 items-center justify-between px-5 py-3">
                <h5 class="font-bold text-lg" id="modalTitle">Add Admin User</h5>

                <button type="button" class="text-gray-600" onclick="hideModal()">✕</button>
            </div>

            <div class="p-5">

                <form id="adminUserForm" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Name</label>
                        <input type="text" name="name" id="nameInput"
                            class="w-full border p-2 rounded" required>
                        @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" id="emailInput"
                            class="w-full border p-2 rounded" required>
                        @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Role</label>
                        <select name="role" id="roleInput" class="w-full border p-2 rounded">
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                        @error('role')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 flex gap-2 items-center">
                        <input type="checkbox" name="is_2fa_enabled" id="twoFactorInput">
                        <label class="font-medium">Enable 2FA</label>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" class="btn btn-danger mr-3" onclick="hideModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary ml-3">Save</button>
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
        document.getElementById('adminUserModal').classList.remove('hidden');
    }

    function hideModal() {
        document.getElementById('adminUserModal').classList.add('hidden');
    }

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Add Admin User';

        // Clear old values
        document.getElementById('nameInput').value = '';
        document.getElementById('emailInput').value = '';
        document.getElementById('roleInput').value = 'admin';
        document.getElementById('twoFactorInput').checked = false;

        const form = document.getElementById('adminUserForm');
        form.action = "{{ route('admin.users.store') }}";
        document.getElementById('formMethod').value = 'POST';

        showModal();
    }

    function openEditModal(admin) {
        document.getElementById('modalTitle').innerText = 'Edit Admin User';

        document.getElementById('nameInput').value = admin.name;
        document.getElementById('emailInput').value = admin.email;
        document.getElementById('roleInput').value = admin.role;
        document.getElementById('twoFactorInput').checked = admin.is_2fa_enabled == 1;

        const form = document.getElementById('adminUserForm');
        form.action = `/admin/users/${admin.id}`;
        document.getElementById('formMethod').value = 'PUT';

        showModal();
    }
</script>
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: @json($errors->first()),
            showConfirmButton: false,
            timer: 5000,
        });
    });
</script>
@endif
@endsection