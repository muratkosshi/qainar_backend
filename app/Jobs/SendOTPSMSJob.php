<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mobizon\MobizonApi;

class SendOTPSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $phone;
    protected $code;
    /**
     * Create a new job instance.
     */
    public function __construct($phone, $code)
    {
        $this -> phone = $phone;
        $this -> code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $api = new MobizonApi(env("MOBIZON_API"), 'api.mobizon.kz');
        // API call to send a message
        // API call to send a message
        if ($api->call('message',
            'sendSMSMessage',
            array(
                // Recipient international phone number
                'recipient' => "7{$this -> username}",
                // Message text
                'text' => "Qainar. Ваш код: {$this -> code}",
                // Alphaname is optional, if you don't have registered alphaname, just skip this parameter and your message will be sent with our free common alphaname, if it's available for this direction.
//                'from' => 'YourAlpha',
                // Message will be expired after 1440 min (24h)
                'params[validity]' => 1440
            ))
        ) {
            // Get message ID assigned by our system to request its delivery report later.
            $messageId = $api->getData('messageId');

            if (!$messageId) {
                // Message is not accepted, see error code and data for details.
            }
            // Message has been accepted by API.
        } else {
            // An error occurred while sending message
            echo '[' . $api->getCode() . '] ' . $api->getMessage() . 'See details below:' . PHP_EOL . print_r($api->getData(), true) . PHP_EOL;
        }
    }
}
