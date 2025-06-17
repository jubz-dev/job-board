<?php

namespace App\Http\Controllers;

use App\Events\JobStatusUpdated;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ModeratorController extends Controller
{

    /**
     * Handle moderation decision.
     *
     * @param Request $request
     * @param JobPost $jobPost
     * @param UrlGenerator $url
     * @param Dispatcher $events
     * @return Response|RedirectResponse
     */
    public function handle(
        Request $request,
        JobPost $jobPost,
        UrlGenerator $url,
        Dispatcher $events
    ) {
        if (! $url->hasValidSignature($request)) {
            return response('Invalid or expired link', Response::HTTP_FORBIDDEN);
        }

        $action = $request->query('action');

        if (!in_array($action, [JobPost::STATUS_APPROVED, JobPost::STATUS_SPAM], true)) {
            return response('Invalid action', Response::HTTP_BAD_REQUEST);
        }

        try {
            $jobPost->update(['status' => $action]);
            $events->dispatch(new JobStatusUpdated($jobPost));
        } catch (\Throwable $e) {
            Log::error('Failed to moderate job post', [
                'error' => $e->getMessage(),
                'job_post_id' => $jobPost->id,
            ]);

            return response('Unable to process moderation at this time.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $redirectUrl = rtrim(env('FRONTEND_URL'), '/') . "/moderate-result?action={$action}";

        return redirect()->away($redirectUrl);
    }
}
