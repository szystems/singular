<?php

namespace sisVentasWeb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioFormRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'foto'=>'mimes:jpg,jpeg,bmp,png|max:10000',
            'email' => 'required|string|email|max:255|unique:users',
            'tipo_usuario' => 'required|string|max:45'
        ];
    }
}
