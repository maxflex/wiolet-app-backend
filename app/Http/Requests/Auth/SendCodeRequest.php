<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Redis;

class SendCodeRequest extends FormRequest
{
    /**
     * Действие авторизовано только в случае, если кода в кэше нет
     */
    public function authorize()
    {
        return Redis::get(cacheKey('codes', $this->phone)) === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'is_phone']
        ];
    }
}
