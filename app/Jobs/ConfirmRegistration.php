<?php

namespace App\Jobs;

use App\Modules\Admin\User\Models\TempUser;
use App\Modules\Admin\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OTPHP\TOTP;

class ConfirmRegistration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::where('phone', $this->phone)->first();

        if ($user && $user->verified === false) {
            $totp = TOTP::create($user->otp_secret);

            if ($totp->verify($this->otp_code)) {
                User::update([
                    'verified' => true,
                ]);

                $user->delete();
            }
        }




    }
}
