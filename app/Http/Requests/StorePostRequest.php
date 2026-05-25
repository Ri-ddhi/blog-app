<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'   => 'required|max:255',
            'slug'    => 'required|unique:posts,slug|max:5',
            'body'    => 'required|min:50',
            'status'  => 'required|in:submitted,draft',
            'categories' => 'required|array|min:1',
        'categories.*' => 'exists:categories,id',
        ];
    }
    public function messages(): array
    {
        return ['title.required' => 'Provide the title.',
            'slug.required'  => 'Slug should contain an underscore (_).',
            'body.min'       => 'The body should be a minimum of 50 characters.',
            'body.required'  => 'The body field cannot be left blank.',
            'categories.min' => "Please add at least one category"];

    }

}
