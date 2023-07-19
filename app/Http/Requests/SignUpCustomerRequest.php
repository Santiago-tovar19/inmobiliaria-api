<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponseController;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="SignUpCustomerRequest",
 *     title="Sign Up Customer Request",
 *     description="Sign Up Customer Request schema",
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Email of the customer",
 *         example="johndoe@example.com"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="First name of the customer",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="Password of the customer",
 *         example="password123"
 *     )
 * )
 */
class SignUpCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "email" => "required|email|unique:users",
            "first_name" => "required",
            "password" => "required",
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
	{
		$response = ApiResponseController::validationErrorResponse($validator);
		throw new \Illuminate\Validation\ValidationException($validator, $response);
	}
}
