<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\policies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ClientPolicyController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth.client');
    // }

    public function index()
    {
        $policies = policies::all();
        return view('client.dashboard', compact('policies'));
    }

    // 1) Return a blade that contains the viewer + watermark overlay
    public function showViewer(policies $policy)
    {
        $user = auth('client')->user();

        if (! $policy->is_published) {
            abort(404);
        }

        // Log view attempt (you might prefer logging when streaming)
        DB::table('policy_views')->insert([
            'policy_id' => $policy->id,
            'client_user_id' => $user->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'viewed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // pass minimal data. The actual file is fetched from stream route.
        return view('client.policies.viewer', compact('policy'));
    }

    // 2) Stream the PDF bytes from private storage (no public URL)
    public function streamPdf(Request $request, policies $policy)
    {

        if (! $policy->is_published) abort(404);

        // build path: assume files stored at storage/app/policies/private/{file_path}
        $path = $policy->file_path; // adapt to your storage layout

        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        // Stream file and set headers to inline (browser shows it). No public URL exposed.
        return response()->stream(function () use ($path) {
            $stream = Storage::disk('private')->readStream($path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache','X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
