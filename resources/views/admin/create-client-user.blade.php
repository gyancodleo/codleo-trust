@extends('admin.layouts.main')

@section('main-content')
<style>
    .nice-select-search {
        width: 100%;
        padding: 0.25rem;
        border: 0.5px solid #000;
        border-radius: 0.25rem;
    }
</style>
<div class="dvanimation animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="basic">
        <div class="panel flex items-center overflow-x-auto whitespace-nowrap p-3 justify-between">
            <h1 class="text-xl font-bold">Client Users</h1>
            <div class="flex justify-between">
                <button type="button" onclick="openCreateModal()" class="btn btn-primary mr-3">Create</button>
                <button type="button" onclick="openAssignPolicy()" class="btn btn-primary ml-3">Assign Policies</button>
            </div>
        </div>
        <div class="panel mt-6">
            @if(session('success'))
            <div class="text-success">{{ session('success') }}</div>
            @endif

            <table class="w-full text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Name</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Company Name</th>
                        <th class="p-2">2FA</th>
                        <th class="p-2">Created By</th>
                        <th class="p-2">Assign Policy</th>
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
                        <td class="p-2 {{ $client->assigned_policies_count > 0 ? 'text-primary' : 'text-gray-400' }}">
                            @if($client->assigned_policies_count > 0)
                            <a href="javascript:void(0)" onclick="openAssignedPoliciesModal({{ $client->id }})"><i class="fa-regular fa-eye text-primary"></i>&nbsp;View Policies</a>
                            @else
                            <span class="text-gray-400">Not Assigned</span>
                            @endif
                        <td class="p-2 flex gap-2">
                            <button onclick="openEditModal({{ $client }})" class="text-success"><i class="fa-regular fa-pen-to-square text-success"></i></button>
                            <form action="{{ route('admin.clients.destroy', $client->id) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this client user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger"><i class="fa-solid fa-trash-can text-danger"></i></button>
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

    <div class="flex items-start justify-center min-h-screen px-4" onclick="hideModal('adminUserModal')">

        <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
            onclick="event.stopPropagation()">

            <div class="flex bg-gray-100 items-center justify-between px-5 py-3">
                <h5 class="font-bold text-lg" id="modalTitle">Add Client User</h5>

                <button type="button" class="text-gray-600" onclick="hideModal('adminUserModal')">✕</button>
            </div>

            <div class="p-5">

                <form id="clientUserForm" method="POST">
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
                        <input type="email" name="email" id="emailInput"
                            class="w-full border p-2 rounded" required>
                        @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Company Name</label>
                        <input type="text" name="company_name" id="companyInput"
                            class="w-full border p-2 rounded" required>
                        @error('company_name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4 flex gap-2 items-center">
                        <input type="checkbox" name="is_2fa_enabled" id="twoFactorInput">
                        <label class="font-medium">Enable 2FA</label>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" class="btn btn-danger mr-3" onclick="hideModal('adminUserModal')">Cancel</button>
                        <button type="submit" class="btn btn-primary ml-3">Save</button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</div>

<div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" id="assignPolicyModal">

    <div class="flex items-start justify-center min-h-screen px-4" onclick="hideModal('assignPolicyModal')">

        <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
            onclick="event.stopPropagation()">

            <div class="flex bg-gray-100 items-center justify-between px-5 py-3">
                <h5 class="font-bold text-lg" id="modalTitle">Assign Policies</h5>

                <button type="button" class="text-gray-600" onclick="hideModal('assignPolicyModal')">✕</button>
            </div>

            <div class="p-5">

                <form id="assignPolicyForm" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Select Client</label>
                        <select name="client_users" id="client_user_select"
                            class="w-full border p-2 rounded selectize" required>
                            <option value="">-- Select Client --</option>
                            @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Select Policies</label>
                        <select name="policies[]" id="policies_select" multiple="multiple"
                            class="w-full border p-2 rounded selectize" required>
                            <option value="">-- Select Policies --</option>
                            @foreach ($policies as $policy)
                            <option value="{{ $policy->id }}">{{ $policy->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" class="btn btn-danger mr-3" onclick="hideModal('assignPolicyModal')">Cancel</button>
                        <button type="submit" class="btn btn-primary ml-3">Assign</button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</div>

<div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" id="viewAssignedPolicies">

    <div class="flex items-start justify-center min-h-screen px-4" onclick="hideModal('viewAssignedPolicies')">

        <div class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
            onclick="event.stopPropagation()">

            <div class="flex bg-gray-100 items-center justify-between px-5 py-3">
                <h5 class="font-bold text-lg" id="modalTitle">Assigned Policies</h5>

                <button type="button" class="text-gray-600" onclick="hideModal('viewAssignedPolicies')">✕</button>
            </div>
            <div id="assignedPoliciesContent" class="p-5">
                <p class="text-gray-500">Loading...</p>
            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    function showModal(value) {
        document.getElementById(value).classList.remove('hidden');
    }

    function hideModal(value) {
        document.getElementById(value).classList.add('hidden');
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

        showModal('adminUserModal');
    }

    function openEditModal(client) {
        document.getElementById('modalTitle').innerText = 'Edit client User';
        document.getElementById('nameInput').value = client.name;
        const emailInput = document.getElementById('emailInput');
        emailInput.value = client.email;
        emailInput.setAttribute('readonly', true);
        emailInput.setAttribute('disabled', true);
        document.getElementById('companyInput').value = client.company_name;
        document.getElementById('twoFactorInput').checked = client.is_2fa_enabled == 1;

        if (emailInput && !document.getElementById('email-warning')) {
            const warning = document.createElement('span');
            warning.id = 'email-warning';
            warning.classList.add("block", "mt-1", "text-xs", "text-gray-600");
            warning.innerText =
                "Email is not editable. If you want to edit it, delete the current user and create a new one.";

            emailInput.after(warning);
        }

        const form = document.getElementById('clientUserForm');
        form.action = `/admin/clients/${client.id}`;
        document.getElementById('formMethod').value = 'PUT';

        showModal('adminUserModal');
    }

    function openAssignPolicy() {
        document.getElementById('modalTitle').innerText = 'Assign Policies Clients';

        // Clear old values
        document.getElementById('nameInput').value = '';
        document.getElementById('emailInput').value = '';
        document.getElementById('companyInput').value = '';
        document.getElementById('twoFactorInput').checked = false;

        const form = document.getElementById('assignPolicyForm');
        form.action = "{{ route('admin.policy.assign') }}";
        document.getElementById('formMethod').value = 'POST';

        showModal('assignPolicyModal');
    }

    function openAssignedPoliciesModal(clientId) {
        const modal = document.getElementById('viewAssignedPolicies');
        const content = document.getElementById('assignedPoliciesContent');

        modal.classList.remove('hidden');
        content.innerHTML = '<p class="text-gray-500">Loading...</p>';

        fetch(`/admin/clients/${clientId}/assigned-policies`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    content.innerHTML = '<p>No policies assigned.</p>';
                    return;
                }

                let html = '<ul class="space-y-2">';
                data.forEach(policy => {
                    html += `
                    <li class="border p-2 rounded">
                        <div class="font-medium">${policy.title}</div>
                        <div class="text-sm text-gray-500">
                            Category: ${policy.category}
                        </div>
                    </li>
                `;
                });
                html += '</ul>';

                content.innerHTML = html;
            })
            .catch(() => {
                content.innerHTML =
                    '<p class="text-red-500">Failed to load policies.</p>';
            });
    }
</script>
<script>
    $(document).ready(function() {
        $('#policies_select').select2({
            placeholder: 'Select Policies',
            width: '100%',
            dropdownParent: $('#assignPolicyModal')
        });
        $('#client_user_select').select2({
            placeholder: 'Select Client',
            width: '100%',
            dropdownParent: $('#assignPolicyModal')
        });

        $('#client_user_select').on('change', function() {
            const clientId = this.value;
            const policySelect = document.getElementById('policies_select');

            // Reset selections
            [...policySelect.options].forEach(opt => opt.selected = false);

            if (!clientId) return;

            fetch(`/admin/clients/${clientId}/assigned-policies-ids`)
                .then(res => res.json())
                .then(policyIds => {
                    [...policySelect.options].forEach(option => {
                        if (policyIds.includes(parseInt(option.value))) {
                            option.selected = true;
                        }
                    });

                    $('#policies_select').trigger('change');
                })
                .catch(() => {
                    console.error('Failed to load assigned policies');
                });
        });
    });
</script>
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: @json($errors - > first()),
            showConfirmButton: false,
            timer: 5000,
        });
    });
</script>
@endif
@endsection