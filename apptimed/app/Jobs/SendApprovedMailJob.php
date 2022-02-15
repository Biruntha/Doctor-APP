<?php

namespace App\Jobs;

use App\Mail\OrderApprovedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mail;
class SendApprovedMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
        // Log::debug($this->details);
        
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new OrderApprovedEmail($this->details );
        Mail::to($this->details['order']->customerDetails->userDetails->email)->send($email);
    }
}
