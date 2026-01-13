<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignPolicyToUser;
use App\Models\policies_category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Throwable;

class PolciyCategoryController extends Controller
{
    public function index()
    {
        $categories = policies_category::all();
        return view('admin.policies.category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' =>
                [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[a-zA-Z0-9 ]+$/',
                    'unique:policies_category,name',
                ]
            ],
            [
                'name.regex' => 'Policy category must only contain letters, numbers, and spaces.',
                'name.unique' => 'Policy category already exist with this name.',
            ]
        );

        DB::beginTransaction();

        try {

            policies_category::create(['name' => $request->name]);

            DB::commit();

            return back()->with(
                'toast',
                [
                    'type' => 'success',
                    'message' => 'Policy Category created successfully.'
                ]
            );
        } catch (Throwable $e) {

            DB::rollBack();

            Log::error("Failed to create category", [
                'error' => $e->getMessage(),
            ]);

            return back()->with(
                'toast',
                [
                    'type' => 'error',
                    'message' => 'failed to create policy Category.'
                ]
            );
        }
    }

    public function updateCategory(Request $request, $category)
    {
        $category = policies_category::findOrFail($category);

        $request->validate(
            [
                'name' =>
                [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[a-zA-Z0-9 ]+$/',
                    'unique:policies_category,name',
                ]
            ],
            [
                'name.regex' => 'Policy category must only contain letters, numbers, and spaces.',
                'name.unique' => 'Policy category already exist with this name.',
            ]
        );

        DB::beginTransaction();

        try {

            $category->update(['name' => $request->name]);

            DB::commit();

            return back()->with(
                'toast',
                [
                    'type' => 'success',
                    'message' => 'Policy Category Updated successfully.'
                ]
            );
        } catch (Throwable $e) {

            DB::rollBack();

            Log::error("Failed to upate category", [
                'error' => $e->getMessage(),
            ]);

            return back()->with(
                'toast',
                [
                    'type' => 'error',
                    'message' => 'failed to update policy Category.'
                ]
            );
        }
    }

    public function destroy(policies_category $category)
    {
        if ($category->policies()->exists()) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Category cannot be deleted because policies are assigned.',
            ]);
        }

        try {

            $category->delete();

            return back()->with('toast', [
                'type' => 'success',
                'message' => 'Category deleted successfully.',
            ]);
        } catch (Throwable $e) {

            Log::error('Policy category delete failed', [
                'category_id' => $category->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Unable to delete category. It may be in use.',
            ]);
        }
    }
}
