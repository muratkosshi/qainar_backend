<?php

namespace App\Modules\Auth;

use App\Jobs\SendOTPSMSJob;
use App\Modules\Admin\User\Models\User;
use OTPHP\HOTP;
use OTPHP\TOTP;
use Mobizon\MobizonApi;
class OTPService
{

    /**
     * Send OTP to a user
     *
     * @param User $user [explicite description]
     * @return bool|string
     */
    public function sendOTP(User $user): bool|string
    {

        $secret = $this->getUserSecret($user);

        // we generate otp with that secret
        $code = $this->generateOTP($secret);


        return $code;
        // Получаем номер телефона пользователя или обрабатываем исключение
        try {
            $phone = $user->username;
        } catch (\Exception $exception) {
            // Обработка исключения (например, логирование ошибки)
            return $exception;
        }
        SendOTPSMSJob::dispatch($user->username, $code);

        return true;


    }



    public function generateHOTP(int $counter = 5, string $digest = "sha256"):string
    {
        $otpGenerete = HOTP::generate();
        $otpGenerete -> setCounter($counter);
        $otpGenerete->setDigest($digest);
        $otpGenerete->setDigits(4);
        $otpSecret = $otpGenerete -> getSecret();
        return $otpSecret;
    }


    /**
     * Generates and OTP code from secret and timestamp (time token)
     *
     * @param $secret $secret [explicite description]
     *
     * @return string
     */
    protected function generateOTP($secret)
    {

        $otp = HOTP::create($secret);
        $otp->setDigits(4);
        $code = $otp->at(4);

        return $code;
    }

    /**
     * We retrieve the secret for creating our code
     *
     * @param $user $user [explicite description]
     *
     * @return string
     */
    protected function getUserSecret($user)
    {
        // if user has a secret
        if($user->otp_secret) {
            return $user->otp_secret;
        }
        else{

            return response()->json(['message' => "Пользователь отсуствует"], 422);
        }
        // user doesn't we create one for the user
    }

    /**
     * Verify that the incoming code is valid
     *
     * @param $user $user [explicite description]
     * @param $code $code [explicite description]
     *
     * @return bool
     */
    public function verifyOTP($user, $code)
    {

        $secret = $this -> getUserSecret($user);


        $otp = HOTP::createFromSecret($secret);

        $res = $otp ->verify($code, 5);

        return $res;
    }
}