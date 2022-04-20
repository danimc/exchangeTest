<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class RegisterRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'     => 'required|string|email|max:255|unique:users',
            'name'      => 'required|string|max:200 ',
            'password'  => 'required|string|min:3'
        ];
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
           'ok'    => false,
           'mensaje'    => 'Error de Solicitud',
           'data'       => $validator->errors()
       ],400));
    }
}
