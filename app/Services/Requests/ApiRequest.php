<?php

declare(strict_types=1);

namespace App\Services\Requests;

use App\Services\Response\ResponseServise;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ApiRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator)) -> errors();

//        throw (new ValidationException($validator))
//            ->errorBag($this->errorBag)
//            ->redirectTo($this->getRedirectUrl());
        throw new HttpResponseException(
            ResponseServise::sendJsonResponse(
             false,
             JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                $errors
            )
        );
    }
}
