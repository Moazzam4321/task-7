<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'password' => 'required|min:8|regex:/^.*([a-zA-Z])(?=.*[0-9]).*/|confirmed',
        ];
    }
    
    protected function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json([

            'errors' => $validator->errors(),

            'status' => false

          ], 422));

    }
}
