<?php

namespace Webkul\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForm extends FormRequest
{
    protected $rules;

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
        dd(request()->input());
        $this->rules = [
            'name' => 'required',
            'email' => 'email|unique:admins,email',
            'password' => 'nullable|confirmed',
            'status' => 'present',
            'role_id' => 'required'
        ];

        if ($this->method() == 'PUT') {
            $this->rules['email'] = 'email|unique:admins,email,' . $this->route('id');
        }

        return $this->rules;
    }
}
