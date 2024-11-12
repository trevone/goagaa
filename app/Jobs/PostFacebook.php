<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostFacebook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

        //1497024704346160
        // EAAVRiTm4ZBDABOwQa35zFfTAlKq97DJRSZCfgyiQAoC3exLRVyl0OtQYENKxOzjdYod14HbbXkq2rE0rrsj5GGz3i0Fc8g3ChlP4hnZCqISs5CPuumZA28Mhia5aw5HrK16D2mbumjR98rfZBsCvcv2LTNaoelyJNaEx4d1TcJhvmIQqodH47huWm5KzykAOyBYLNeVnTZCzRSWTZBQ4AELeFCQ9rQu7Xko
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
