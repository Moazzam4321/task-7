<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
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
            //
            'name' => [
                         'alpha',
                         'max:value(50)'
                        ],
            'email' => [
                         'email',
                        // 'unique,email'
                        ],
            'password' =>[
                             'confirmed',
                            //  Password::min(8)
                            //            ->mixedCase()
                            //            ->letters()
                            //            ->numbers()
                            //            ->symbols()
                            //            ->uncompromised()
                        ],
            'age' => [
                        'integer',
                        'max:value(150)'
                    ],
            'profile_photo' =>[
                                'image',
                                ' mimes:jpeg,png,jpg,gif,svg',
                                 'max:1024'
                            ]   
        ];
    }
    public function messages()
    {
        return[
            'name.alpha'                => 'Only alpabeths are allowed for name',
            'name.max:value(50)'        => 'Name cannot be greater than 50 characters',
            'email.email:rfc,dns'       => 'Enter Correct Format Of Email as www@gmail.com',
            'password.confirmed'        => 'Password and Confirm Password Are Not Matched',
            'password.Password::min(8)' => 'Password Not Be Less than 8 characters',
            'age.integer'               => 'Only Integer Values Are Allowed For Age ',
            'age.max:value(150)'        => 'Age cannot be greater than 150 characters',
            'profile_photo.image'       => 'Only Images Types Are Allowed As jpeg,png,jpg,gif,svg ',
            'profile_photo.max:1024'    => 'Iamge not be greater than 1 Gb',
            'profile_photo.mimes'       => 'Only Images Types Are Allowed As jpeg,png,jpg,gif,svg ' ,
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
