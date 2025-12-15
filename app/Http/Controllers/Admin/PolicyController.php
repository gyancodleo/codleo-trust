<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\policies;
use App\Models\policies_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'title'        => 'required',
            'category_id'  => 'nullable|exists:policies_category,id',
            'description'  => 'nullable|string',
            'file'         => 'nullable|mimes:pdf|max:10240',
            'is_published' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'title',
            'category_id',
            'description'
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('policies', 'private');
        }

        $data['is_published'] = $request->boolean('is_published', false);
        $data['created_by']   = Auth::guard('admin')->id();
        $data['updated_by']   = Auth::guard('admin')->id();

        policies::create($data);

        return redirect()->route('admin.policies.index')->with('success', 'Policy created.');
    }

    public function edit(policies $policy)
    {
        $categories = policies_category::all();
        return view('admin.policies.edit', compact('policy', 'categories'));
    }

    public function update(Request $request, policies $policy)
    {
        $request->validate([
            'title'        => 'required',
            'category_id'  => 'nullable|exists:policies_category,id',
            'description'  => 'nullable|string',
            'file'         => 'nullable|mimes:pdf|max:10240',
            'is_published' => 'nullable|boolean',
        ]);

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

        return redirect()->route('admin.policies.index')->with('success', 'Policy updated.');
    }

    public function destroy(policies $policy)
    {
        $policy->deleteFile();
        $policy->delete();

        return back()->with('success', 'Policy deleted.');
    }

    public function publish(policies $policy)
    {
        $policy->update([
            'is_published' => true,
            'updated_by'   => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', 'Policy published.');
    }

    public function unpublish(policies $policy)
    {
        $policy->update([
            'is_published' => false,
            'updated_by'   => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', 'Policy unpublished.');
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
