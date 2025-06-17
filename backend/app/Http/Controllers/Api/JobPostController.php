<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\FirstJobSubmissionMail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class JobPostController extends Controller
{
    /**
     * Display a listing of job posts, including approved internal and external sources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $jobPosts = JobPost::query()
                ->where(function ($query) {
                    $query->where('source', JobPost::SOURCE_EXTERNAL)
                        ->orWhere(function ($q) {
                            $q->where('source', JobPost::SOURCE_INTERNAL)
                                ->where('status', JobPost::STATUS_APPROVED);
                        });
                })
                ->orderByDesc('created_at')
                ->get([
                    'id', 'title', 'description', 'source', 'source_url', 'created_at', 'status'
                ]);

            return response()->json($jobPosts, Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Job post fetch failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Unable to fetch job posts at the moment.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly submitted job post, sending a moderation email if it’s the first by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'email' => 'required|email',
        ]);

        try {
            $isFirst = !JobPost::where('email', $validated['email'])->exists();

            $jobPost = JobPost::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'email' => $validated['email'],
                'source' => JobPost::SOURCE_INTERNAL,
                'status' => JobPost::STATUS_PENDING,
            ]);

            if ($isFirst) {
                $approveUrl = URL::signedRoute('moderate', [
                    'jobPost' => $jobPost->id,
                    'action' => JobPost::STATUS_APPROVED,
                ]);

                $spamUrl = URL::signedRoute('moderate', [
                    'jobPost' => $jobPost->id,
                    'action' => JobPost::STATUS_SPAM,
                ]);

                Mail::to(config('mail.moderator_address'))
                    ->send(new FirstJobSubmissionMail($jobPost, $approveUrl, $spamUrl));
            }

            return response()->json([
                'message' => 'Job submitted successfully.',
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            Log::error('Job post creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Database error occurred while creating the job post.',
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            Log::critical('Unexpected error during job post creation', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Unexpected error occurred. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
