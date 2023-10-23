<?php

declare(strict_types=1);

namespace App\Services\Response;

class ResponseServise
{
    private static function responsePrams(bool $status, array $errors =[], array $data =[])
    {
        return[
          'status'=>$status,
          'errors'=>(object) $errors,
          'data' => (object) $data,
        ];
    }

    public static function sendJsonResponse(bool $status, int $code = 200, array $errors = [], array $data = []){
        return response()->json(
          self::responsePrams($status, $errors, $data),
            $code
        );
    }

    public static function success($data =[]){
        return self::sendJsonResponse(true,200, [], $data);
    }

    public static function notFound($data =[]){
        return self::sendJsonResponse(false,404, [], []);
    }

    public static function create($data = [])

    {
        return self::sendJsonResponse(true,201, [], $data);
    }
}