<?php

namespace App\Http\Requests\Comments;

use App\Http\Requests\ApiRequest;

class StoreRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'post_id' => 'required|integer|exists:posts,id',
            'text' => 'required|string',
            'user_id' => 'required|integer|exists:users,id'
        ];
    }
}
