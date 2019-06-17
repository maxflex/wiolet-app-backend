<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Hash;

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
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['nullable', 'required_with:password_confirmation', 'string', 'min:6', 'confirmed'],
            'current_password' => ['required_with:password', 'string', 'min:6'],
        ];
    }

    public function withValidator($validator)
    {
        // checks user current password
        // before making changes
        $validator->after(function ($validator) {
            if (isset($this->current_password)) {
                if (! Hash::check($this->current_password, auth()->user()->password) ) {
                    $validator->errors()->add('current_password', __('passwords.wrong-current-password'));
                }
            }
        });
    }
}
