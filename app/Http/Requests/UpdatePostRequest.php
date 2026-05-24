<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
        $postId = is_object($this->post) ? $this->post->id : $this->post;
        return [
            'title'   => 'required|max:255',
            'slug'    => 'required|max:255|unique:posts,slug,'.$postId,
            'body'    => 'required',
            'status'  => 'required|in:submitted,draft'
        ];
    }
    public function messages(): array
    {
        return ["title.required" => "Provide the title", "slug.required" => "slug should contain underscore _" ,"body" => "is should be minimun 50 characters"];

    }
}
