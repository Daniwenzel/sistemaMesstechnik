<?php

namespace Messtechnik\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'nome' => 'bail|required|string|max:255|unique:empresas',
            'cnpj' => 'bail|string|nullable',
            'phone' => 'bail|string|nullable',
            'email' => 'bail|email|nullable|max:255|unique:empresas',
        ];
    }

    /*public function messages() {
        return [
            'email.required' => 'Email necessario',
            'name.required' => 'Nome necessario',
    ];
    }*/
}
