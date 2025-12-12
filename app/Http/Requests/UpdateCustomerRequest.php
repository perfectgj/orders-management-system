<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('customer') ? $this->route('customer')->id : $this->route('id');
        return [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:customers,email,{$id}",
            'phone' => 'nullable|string|max:20',
        ];
    }
}
