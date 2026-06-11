<?php

namespace App\Http\Requests\Admin\Content;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
        $menuId = $this->route('menu')?->id;

        return [
            'name' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
            'url' => 'required|max:500|min:5|url',
            'parent_id' => 'nullable|min:1|regex:/^[0-9]+$/u|exists:menus,id|not_in:' . $menuId,
            'status' => 'required|numeric|in:0,1'
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.not_in' => 'A menu cannot be its own parent.',
        ];
    }
    
}
