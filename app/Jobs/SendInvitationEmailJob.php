<?php

namespace App\Jobs;

use App\Mail\InvitationEmailMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvitationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $senderName;
    private $groupName;

    private $groupId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($senderName,$groupName, $groupId ,$email)
    {
        $this->email = $email;
        $this->senderName = $senderName;
        $this->groupName = $groupName;
        $this->groupId = $groupId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new InvitationEmailMailable($this->senderName,$this->groupName,$this->groupId));
    }
}
