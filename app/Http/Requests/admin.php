<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class admin extends Request
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
            //'name', 'email', 'password'
            'name'      =>  'required|min:5',
            'email'     =>  'required|email|unique:users',
            'password'  =>  'required|confirmed|min:6',
        ];
    }
}