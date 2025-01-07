<?php

namespace App\Http\Requests\Posts;

use App\Enums\PostStatus;
use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

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
            'category_id' => 'nullable|integer|exists:categories,id',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'thumbnail' => 'image|nullable|max:500',
            'status' => ['string', 'required', new Enum(PostStatus::class)],
            'user_id' => 'integer|exists:users,id',
            'views' => 'nullable|integer'
        ];
    }

    public function prepareForValidation() {
        $user = Auth::user();
        $this->merge([
            'user_id' => $user->id
        ]);
    }
}
