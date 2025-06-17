<?php

namespace App\Console\Commands;

use App\Models\JobPost;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncExternalJobPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobposts:sync-external';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync external job posts from Personio XML feed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://mrge-group-gmbh.jobs.personio.de/xml';

        try {
            $xml = simplexml_load_file($url);
        } catch (\Exception $e) {
            $this->error('Failed to fetch XML feed.');
            return;
        }

        foreach ($xml->position as $job) {
            $title = (string) $job->name;
            $description = (string) ($job->jobDescriptions->jobDescription[0]->value ?? '');

            $jobId = (string) $job->id;
            $link = "https://mrge-group-gmbh.jobs.personio.de/job/$jobId";

            if (empty($title)) {
                $this->warn("Skipped a job due to missing title.");
                continue;
            }

            $exists = JobPost::where('source', 'external')
                ->where('source_url', $link)
                ->where('title', $title)
                ->exists();

            if (!$exists) {
                JobPost::create([
                    'id' => (string) Str::uuid(),
                    'title' => $title,
                    'description' => strip_tags($description),
                    'email' => null,
                    'source' => 'external',
                    'source_url' => $link,
                    'status' => 'approve',
                ]);

                $this->info("Added external job post: $title");
            }
        }

        $this->info('External job posts sync complete.');
    }
}
