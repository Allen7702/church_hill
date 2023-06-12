<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PasswordCheck;

class PasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => ['sometimes', 'min:3', new PasswordCheck],
            'password' => ['sometimes', 'min:8', 'confirmed', 'different:old_password'],
            'password_confirmation' => ['sometimes', 'min:8'],
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => __('current password'),
        ];
    }
}
