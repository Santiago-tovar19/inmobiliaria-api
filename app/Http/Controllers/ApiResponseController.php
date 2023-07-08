<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 *     schema="GeneralResponse",
 *     type="object",
 *     @OA\Property(
 *         property="message",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="any"
 *     )
 * )
 * @OA\Schema(
 *     schema="Pagination",
 *     type="object",
 *     @OA\Property(
 *         property="current_page",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items({})
 *     ),
 *     @OA\Property(
 *         property="first_page_url",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="from",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="last_page",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="last_page_url",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="next_page_url",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="path",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="per_page",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="prev_page_url",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="to",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="total",
 *         type="integer"
 *     )
 * )
 * @OA\Schema(
 *     schema="PaginatedResponse",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/GeneralResponse"),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="data",
 *                 ref="#/components/schemas/Pagination"
 *             )
 *         )
 *     }
 * )
 */




class ApiResponseController extends Controller
{

    static public function response(String $message = '', Int $statusCode = 200, $data = false)
		{
				$response = [
						'message' => $message
				];

				if($data){
					$response['data'] = $data;
				}

				return response()->json($response, $statusCode);
		}

		static public function validationErrorResponse(\Illuminate\Contracts\Validation\Validator $validator)
		{
				return new JsonResponse([
						'message' => 'Hay errores en el formulario',
						'errors' => $validator->errors()
				], 422);
		}
}
