<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\policies_category;
use Illuminate\Http\Request;

class PolciyCategoryController extends Controller
{
    public function index()
    {
        $categories = policies_category::all();
        return view('admin.policies.category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        policies_category::create(['name' => $request->name]);

        return redirect()->route('admin.policy.category')->with('success', 'Category created.');
    }

    public function update(Request $request, policies_category $category)
    {
        $request->validate(['name' => 'required']);
        $category->update(['name' => $request->name]);

        return redirect()->route('admin.policy.category')->with('success', 'Updated.');
    }

    public function destroy(policies_category $category)
    {
        $category->delete();
        return back()->with('success', 'Deleted.');
    }
}
