<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponseController;
use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            'brand' => 'required',
            'model' => 'required',
            'placa' => 'unique:cars,placa',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
	{
		$response = ApiResponseController::validationErrorResponse($validator);
		throw new \Illuminate\Validation\ValidationException($validator, $response);
	}
}
