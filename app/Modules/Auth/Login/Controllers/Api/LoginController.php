<?php

declare(strict_types=1);

namespace App\Modules\Auth\Login\Controllers\Api;

use App\Modules\Admin\User\Models\User;
use App\Modules\Auth\Login\Requests\LoginRequest;
use App\Modules\Auth\Register\Controllers\Api\RegisterController;
use App\Modules\Auth\Register\Requests\RegistrationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request):JsonResponse
    {
        if(!request()->password && !$user = $this->validateUser(request('username'))){

            // Создайте экземпляр RegisterController
            $registerController = new RegisterController();

            // Создайте экземпляр RegistrationRequest, передавая ему данные из $request
            $registrationRequest = new RegistrationRequest(request()->all());
            // Вызовите метод регистрации, передавая объект RegistrationRequest
            $otp =  $registerController->register($registrationRequest);
            $user= $this->validateUser(request('username'));
            return response()->json(['username'=>"{$user->username}",
                "otp"=>$otp], 201);
        }
        elseif(request()->password){
            $credentials = request(['username', 'password']);
            if ($token = auth()->attempt($credentials)) {
                return $this->respondWithToken($token);
            }
            else{
                return response()->json(['massage'=>"Пароль неверный"], 422);
            }
        }
        else{
            return response()->json(['username'=>$user->username], 200);
        }


    }

    protected function validateUser($username): User|bool|null
    {
       return User::where('username', preg_replace('/[^0-9]/', '', $username))->first();
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        }   catch (JWTException $exception)
        {
            return response()->json(['message' => ''], ResponseAlias::HTTP_UNAUTHORIZED);
            Log::info($exception);
        }


    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {

        setcookie("token_access", $token,);
        return response()->json(["OK"], Response::HTTP_ACCEPTED);
    }

}
