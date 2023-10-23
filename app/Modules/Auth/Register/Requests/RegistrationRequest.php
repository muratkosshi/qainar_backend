<?php

declare(strict_types=1);

namespace App\Modules\Auth\Register\Requests;

use App\Services\Requests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\JsonResponse;

class RegistrationRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|min:10|max:10',
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'password.min' => 'The password must be at least :min characters long.',
        ];
    }


    /**
     * Get the response for a validation failure.
     *
     * @param  array  $errors
     * @return JsonResponse
     */
    public function response(array $errors): JsonResponse
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $errors,
        ], 422); // You can customize the HTTP status code here (e.g., 422 for Unprocessable Entity).
    }
}
