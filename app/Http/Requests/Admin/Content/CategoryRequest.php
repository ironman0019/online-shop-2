<?php

namespace App\Http\Requests\Admin\Content;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'name' => 'required|max:120|min:2|string',
                'description' => 'required|min:5|string',
                'image' => '',
                'tags' => 'required|string|max:255',
                'status' => 'required|numeric|in:0,1'
            ];
        }
        else
        {
            return [
                'name' => 'required|max:120|min:2|string',
                'description' => 'required|min:5|string',
                'image' => '',
                'tags' => 'required|string|max:255',
                'status' => 'required|numeric|in:0,1'
            ];
        }
    }
}
