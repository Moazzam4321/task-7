<?php

namespace App\Jobs;

use App\Http\Requests\SignUpRequest;
use App\Mail\Verification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailSending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
   public $link,$email,$subject;
    public function __construct($link,$email,$subject)
    {
        //
        $this->link=$link;
        $this->email=$email;
        $this->subject=$subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Mail::to($this->email)->send(new Verification($this->link,$this->subject));
    }
}
