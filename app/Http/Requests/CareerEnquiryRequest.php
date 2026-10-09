<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation for the "apply" form on the Career page of the website.
 * The form sends: name, email, phone, nationality, either career_id (the career being
 * applied for) or apply_for (the position as text), cv (a file) and message.
 *
 * To make Nationality or CV optional, change 'required' to 'nullable' on its line in rules().
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
            'phone'       => trim((string) $this->input('phone')),
            'nationality' => trim((string) $this->input('nationality')),
            'apply_for'   => trim((string) $this->input('apply_for')),
            'message'     => trim((string) $this->input('message')),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'phone'       => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{6,30}$/'],
            'nationality' => ['required', 'string', 'max:100'],
            'career_id'   => ['nullable', 'integer', 'exists:careers,id'],
            'apply_for'   => ['required_without:career_id', 'nullable', 'string', 'max:255'],
            'cv'          => ['required', 'file', 'mimes:pdf,doc,docx', 'max:2048'],   // max is in KB: 2 MB
            'message'     => ['nullable', 'string', 'max:2000'],
            'website'     => ['nullable', 'string', 'max:255'],   // hidden anti-spam field, see the controller
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
            'nationality.required'       => 'Please enter your nationality.',
            'cv.required'                => 'Please attach your CV.',
            'cv.uploaded'                => 'The CV could not be uploaded. Please use a file of 2 MB or less.',
            'cv.mimes'                   => 'The CV must be a PDF or Word file.',
            'cv.max'                     => 'The CV must be 2 MB or less.',
            'message.max'                => 'The message can be at most 2000 characters.',
            'career_id.exists'           => 'This position is no longer open.',
            'apply_for.required_without' => 'Please choose the position you are applying for.',
        ];
    }
}
