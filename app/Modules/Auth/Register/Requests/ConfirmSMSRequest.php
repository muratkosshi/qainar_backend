<?php

declare(strict_types=1);

namespace App\Modules\Auth\Register\Requests;

use App\Services\Requests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ConfirmSMSRequest extends ApiRequest
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
            'otp_code' => 'required',
            'password' => 'required|min:6|max:30'
        ];
    }
}
