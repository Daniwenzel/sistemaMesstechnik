<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            //'id' => 'required',
            'name' => 'bail|required|string|max:255',
            'last_name' => 'bail|string|max:255',
            'aniversario' => 'bail|date',
            'genero' => 'bail|string|max:255',
            'email' => 'bail|required|string|email|max:255|unique:users',
            'password' => 'bail|required|string|min:6|confirmed',
        ];
    }

    /*public function messages() {
        return [
            'email.required' => 'Email necessario',
            'name.required' => 'Nome necessario',
    ];
    }*/
}
