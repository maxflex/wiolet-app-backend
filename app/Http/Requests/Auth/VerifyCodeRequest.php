<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Redis;

class VerifyCodeRequest extends FormRequest
{
    /**
     * Действие авторизовано только в случае, если кода в кэше нет
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
            'phone' => ['required', 'is_phone'],
            'code' => [
                'required',
                function($attribute, $value, $fail) {
                    if (Redis::get(cacheKey('codes', $this->phone)) !== $value) {
                        return $fail(__('validation.wrong-verify-code'));
                    }
                }
            ],
        ];
    }
}
