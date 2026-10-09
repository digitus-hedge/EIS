<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation for the "apply" form on the Career page of the website.
 * The form sends: name, email, phone and either career_id (the career being applied for)
 * or apply_for (the position typed or chosen as text).
 */
class CareerEnquiryRequest extends FormRequest
{
    /** Errors go to their own bag so they never mix with another form on the same page. */
    protected $errorBag = 'careerApply';

    /** After a failed check, come back to the form itself, not the top of the page. */
    protected function getRedirectUrl()
    {
        return parent::getRedirectUrl() . '#career-apply';
    }

    public function authorize(): bool
    {
        return true; // public form
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'      => trim((string) $this->input('name')),
            'email'     => trim((string) $this->input('email')),
            'phone'     => trim((string) $this->input('phone')),
            'apply_for' => trim((string) $this->input('apply_for')),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'phone'     => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{6,30}$/'],
            'career_id' => ['nullable', 'integer', 'exists:careers,id'],
            'apply_for' => ['required_without:career_id', 'nullable', 'string', 'max:255'],
            'website'   => ['nullable', 'string', 'max:255'],   // hidden anti-spam field, see the controller
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'Please enter your name.',
            'email.required'             => 'Please enter your email address.',
            'email.email'                => 'Please enter a valid email address.',
            'phone.required'             => 'Please enter your phone number.',
            'phone.regex'                => 'Please enter a valid phone number.',
            'career_id.exists'           => 'This position is no longer open.',
            'apply_for.required_without' => 'Please choose the position you are applying for.',
        ];
    }
}
