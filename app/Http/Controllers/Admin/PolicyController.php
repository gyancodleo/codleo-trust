<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\policies;
use App\Models\policies_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = policies::with('category')->latest()->paginate(10);
        return view('admin.policies.index', compact('policies'));
    }

    public function create()
    {
        $categories = policies_category::all();
        return view('admin.policies.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'category_id'  => 'required|exists:policies_category,id',
            'description'  => 'required|string',
            'file'         => 'required|mimes:pdf|max:10240',
            'is_published' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->only([
                'title',
                'category_id',
                'description',
            ]);

            // File upload
            if ($request->hasFile('file')) {
                $data['file_path'] = $request
                    ->file('file')
                    ->store('policies', 'private');
            }

            $data['is_published'] = $request->boolean('is_published', false);
            $data['created_by']   = Auth::guard('admin')->id();
            $data['updated_by']   = Auth::guard('admin')->id();

            policies::create($data);

            DB::commit();

            // return back()
            //     ->with('toast', [
            //         'type' => 'success',
            //         'message' => 'Policy created successfully.',
            //         'redirect'=>'admin.policies.index',
            //     ]);
            return redirect()->route('admin.policies.policy')
            ->with('toast', [
                    'type' => 'success',
                    'message' => 'Policy created successfully.',
                    'redirect'=>'admin.policies.index',
                ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Policy creation failed', [
                'admin_id' => Auth::guard('admin')->id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Failed to create policy. Please try again.',
                ]);
        }
    }


    public function edit(policies $policy)
    {
        $categories = policies_category::all();
        return view('admin.policies.edit', compact('policy', 'categories'));
    }

    public function update(Request $request, policies $policy)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'category_id'  => 'required|exists:policies_category,id',
            'description'  => 'required|string',
            'file'         => 'nullable|mimes:pdf|max:10240',
            'is_published' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {

            $data = $request->only([
                'title',
                'category_id',
                'description'
            ]);

            if ($request->hasFile('file')) {
                $policy->deleteFile();
                $data['file_path'] = $request->file('file')->store('policies', 'private');
            }

            $data['is_published'] = $request->boolean('is_published', false);
            $data['updated_by']   = Auth::guard('admin')->id();

            $policy->update($data);

            DB::commit();

            return redirect()->route('admin.policy')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Policy updated Successfully.',
                ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Policy updation failed', [
                'admin_id' => Auth::guard('admin')->id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Failed to update policy. Please try again.',
                ]);
        }
    }

    public function destroy(policies $policy)
    {

        if ($policy->assignedUsers()->exists()) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Policy cannot be deleted while assigned to users.',
            ]);
        }

        DB::beginTransaction();

        try {

            if ($policy->file_path && Storage::disk('private')->exists($policy->file_path)) {
                Storage::disk('private')->delete($policy->file_path);
            }

            $policy->delete();

            DB::commit();

            return back()->with('toast', [
                'type' => 'success',
                'message' => 'Policy deleted successfully.',
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Policy deletion failed', [
                'policy_id' => $policy->id,
                'admin_id'  => Auth::guard('admin')->id(),
                'error'     => $e->getMessage(),
            ]);

            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Unable to delete policy. It may be in use.',
            ]);
        }
    }

    public function publish(policies $policy)
    {
        try {

            $policy->update([
                'is_published' => true,
                'updated_by'   => Auth::guard('admin')->id(),
            ]);

            return back()->with('toast', [
                'type' => 'success',
                'message' => 'Policy published successfully.',
            ]);
        } catch (\Throwable $e) {

            Log::error('Policy publish failed', [
                'policy_id' => $policy->id,
                'admin_id'  => Auth::guard('admin')->id(),
                'error'     => $e->getMessage(),
            ]);

            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Failed to publish policy.',
            ]);
        }
    }

    public function unpublish(policies $policy)
    {
        try {
            $policy->update([
                'is_published' => false,
                'updated_by'   => Auth::guard('admin')->id(),
            ]);

            return back()->with('toast', [
                'type' => 'success',
                'message' => 'Policy unpublished successfully.',
            ]);
        } catch (\Throwable $e) {

            Log::error('Policy unpublish failed', [
                'policy_id' => $policy->id,
                'admin_id'  => Auth::guard('admin')->id(),
                'error'     => $e->getMessage(),
            ]);

            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Failed to unpublish policy.',
            ]);
        }
    }

    public function stream(policies $policy)
    {
        if (!auth('admin')->check()) {
            abort(403);
        }

        $file = Storage::disk('private')->get($policy->file_path);

        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline') // no download
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function preview(policies $policy)
    {
        return view('admin.policies.preview', compact('policy'));
    }
}
