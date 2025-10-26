<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'feature_video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:51200',

            'modules' => 'nullable|array',
            'modules.*.title' => 'required_with:modules|string|max:255',
            'modules.*.description' => 'nullable|string',

            'modules.*.contents' => 'nullable|array',
            'modules.*.contents.*.type' => 'required|in:text,image,video,link',
            'modules.*.contents.*.title' => 'nullable|string|max:255',
            'modules.*.contents.*.body' => 'nullable|string',
            'modules.*.contents.*.file' => 'nullable|file|max:51200',
            'modules.*.contents.*.url' => 'nullable|url',
        ];
    }
}
