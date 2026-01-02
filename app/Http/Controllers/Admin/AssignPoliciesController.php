<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignPolicyToUser;
use App\Models\ClientUser;
use App\Models\policies;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AssignPoliciesController extends Controller
{
    public function index()
    {
        $clients = AssignPolicyToUser::all();
        return view('admin.policy.assign', compact('clients'));
    }

    public function getAssignedPolicies(ClientUser $client)
    {
        $policies = $client->assignedPolicies()
            ->with('policy.category')
            ->get()
            ->map(fn($item) => [
                'title'    => $item->policy->title,
                'category' => optional($item->policy->category)->name ?? 'Uncategorized',
            ]);

        return response()->json($policies);
    }

    public function getAssignedPolicyIds(ClientUser $client)
    {
        return response()->json(
            $client->assignedPolicies()
                ->pluck('policy_id')
                ->toArray()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_users' => 'required|exists:client_users,id',
            'policies' => 'required|array',
            'policies.*' => 'exists:policies,id',
        ]);

        DB::beginTransaction();

        try {

            AssignPolicyToUser::where('client_user_id', $data['client_users'])->delete();

            AssignPolicyToUser::insert(
                collect($data['policies'])->map(fn($pid) => [
                    'policy_id' => $pid,
                    'client_user_id' => $data['client_users'],
                    'created_by'     => Auth::guard('admin')->id(),
                    'updated_by'     => Auth::guard('admin')->id(),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ])->toArray()
            );

            DB::commit();

            return back()->with(
                'toast',
                [
                    'type' => 'success',
                    'message' => 'Policies assigned successfully'
                ]
            );
        } catch (Throwable $e) {

            DB::rollBack();

            Log::error("Failed to assign polcies to users", [
                'error' => $e->getMessage(),
            ]);

            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Failed to assign policies to user',
            ]);
        }
    }
}
