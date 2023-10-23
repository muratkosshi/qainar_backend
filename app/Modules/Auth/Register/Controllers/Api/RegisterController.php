<?php

namespace App\Modules\Auth\Register\Controllers\Api;

use App\Modules\Admin\User\Models\User;
use App\Modules\Auth\OTPService;
use App\Modules\Auth\Register\Requests\ConfirmSMSRequest;
use App\Modules\Auth\Register\Requests\RegistrationRequest;
use Couchbase\BaseException;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class RegisterController extends Controller
{


    public function register(RegistrationRequest $request): JsonResponse|bool|string
    {
        $otpService = new OTPService();
        $user = new User();
        if(!User::where('username', preg_replace('/[^0-9]/', '', $request->username))->first()) {
            $user->password = Hash::make(Str::random(6));
            $user->username = preg_replace('/[^0-9]/', '', $request->username);
            $user->confirm = false;
            $user->name = '';
            $user->otp_secret = $otpService->generateHOTP();
            $user->role ='client';
            $user->save();
        }
        else
        {
                return response()->json(['massage' => "This is user has in DB",], 200);
        }
            $otp = $otpService->sendOTP($user); // Вызываем метод sendOTP, передавая OTPService в качестве аргумента

        return $otp;


    }

    public function confirm_sms_code(ConfirmSMSRequest $request): JsonResponse
    {
        $user = User::where('username', preg_replace('/[^0-9]/', '', $request->username))->first();
        $otp_service = new OTPService();
        $code = $request -> otp_code;
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if($otp_service->verifyOTP($user, $code))
        {
            try {
                $user->confirm = true;
                $user->password = Hash::make($request->password);
                $user->update();
            }
            catch (BaseException)
            {
                return response()->json(['message' => "Пользователь не создан, повторите попытку"], 422);
            }
             $token = auth()->login($user); //Входит в систему через helper auth библеотеки JWT tymon
             setcookie("token_access", $token);
                return response()->json([
                    'username' => "{$user -> username}",
                    ], 200);
        }
            else
            {
                return response()->json(['message' => "Неверный смс-код"]);
            }
             // Вызываем метод sendOTP, передавая OTPService в качестве аргумента


    }
}