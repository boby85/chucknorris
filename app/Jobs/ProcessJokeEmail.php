<?php

namespace App\Jobs;

use App\Mail\SendJokeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessJokeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct
    (
        public array $emailsSortedByDomain,
        public string $joke
    )
    {}

    public function handle()
    {
        $recipients = $this->emailsSortedByDomain;
        $joke = $this->joke;

        foreach ($recipients as $recipient) {
            Mail::to($recipient)->queue(new SendJokeEmail($joke));
        }
    }
}
