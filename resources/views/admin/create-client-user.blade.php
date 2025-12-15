@extends('admin.layouts.main')

@section('main-content')

<div class="dvanimation animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="basic">
        <div class="panel flex items-center overflow-x-auto whitespace-nowrap p-3 justify-between">
            <h1 class="text-xl font-bold">Client Users</h1>
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
                        <th class="p-2">Company Name</th>
                        <th class="p-2">2FA</th>
                        <th class="p-2">Created By</th>
                        <th class="p-2">Updated By</th>
                        <th class="p-2 w-32">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($clients as $client)
                    <tr class="border-b">
                        <td class="p-2">{{ $client->name }}</td>
                        <td class="p-2">{{ $client->email }}</td>
                        <td class="p-2">{{ $client->company_name }}</td>
                        <td class="p-2">{{ $client->is_2fa_enabled ? 'Enabled' : 'Disabled' }}</td>
                        <td class="p-2">{{ $client->createdBy->name }}</td>
                        <td class="p-2">{{ $client->updatedBy->name }}</td>
                        <td class="p-2 flex gap-2">
                            <button onclick="openEditModal({{ $client }})" class="text-primary">Edit</button>
                            <form action="{{ route('admin.clients.destroy', $client->id) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this client user?')">
                                @csrf 
                                @method('DELETE')
                                <button class="text-danger">Delete</button>
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

{{-- ===========================
        MODAL SECTION
   =========================== --}}
<div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" id="adminUserModal">

    <div class="flex items-start justify-center min-h-screen px-4" onclick="hideModal()">

        <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
            onclick="event.stopPropagation()">

            <div class="flex bg-gray-100 items-center justify-between px-5 py-3">
                <h5 class="font-bold text-lg" id="modalTitle">Add Client User</h5>

                <button type="button" class="text-gray-600" onclick="hideModal()">✕</button>
            </div>

            <div class="p-5">

                <form id="clientUserForm" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Name</label>
                        <input type="text" name="name" id="nameInput"
                            class="w-full border p-2 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Email</label>
                        <input type="email" name="email" id="emailInput"
                            class="w-full border p-2 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Company Name</label>
                        <input type="text" name="company_name" id="companyInput"
                            class="w-full border p-2 rounded" required>
                    </div>

                    <div class="mb-4 flex gap-2 items-center">
                        <input type="checkbox" name="is_2fa_enabled" id="twoFactorInput">
                        <label class="font-medium">Enable 2FA</label>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" class="btn btn-danger" onclick="hideModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary ml-4">Save</button>
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
        document.getElementById('modalTitle').innerText = 'Add Client User';

        // Clear old values
        document.getElementById('nameInput').value = '';
        document.getElementById('emailInput').value = '';
        document.getElementById('companyInput').value = '';
        document.getElementById('twoFactorInput').checked = false;

        const form = document.getElementById('clientUserForm');
        form.action = "{{ route('admin.clients.store') }}";
        document.getElementById('formMethod').value = 'POST';

        showModal();
    }

    function openEditModal(client) {
        document.getElementById('modalTitle').innerText = 'Edit client User';

        document.getElementById('nameInput').value = client.name;
        document.getElementById('emailInput').value = client.email;
        document.getElementById('companyInput').value = client.company_name;
        document.getElementById('twoFactorInput').checked = client.is_2fa_enabled == 1;

        const form = document.getElementById('clientUserForm');
        form.action = `/admin/clients/${client.id}`;
        document.getElementById('formMethod').value = 'PUT';

        showModal();
    }
</script>
@endsection