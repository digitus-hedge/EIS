<?php

namespace App\Http\Requests;

use App\Models\Career;
use Illuminate\Foundation\Http\FormRequest;

/** Validation for adding and editing a career (Admin > Career > Form). */
class CareerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // the route is already behind the admin login
    }

    protected function prepareForValidation(): void
    {
        $description = (string) $this->input('description');

        // An editor that looks empty can still send "<p>&nbsp;</p>": treat that as empty.
        if (Career::plainText($description) === '') {
            $description = '';
        }

        $this->merge([
            'title'       => trim((string) $this->input('title')),
            'location'    => trim((string) $this->input('location')),
            'description' => $description,
        ]);
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'location'    => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:60000'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Please enter the title.',
            'title.max'            => 'The title can be at most 255 characters.',
            'location.required'    => 'Please enter the job location.',
            'location.max'         => 'The job location can be at most 255 characters.',
            'description.required' => 'Please enter the description.',
            'description.max'      => 'The description is too long.',
        ];
    }
}
