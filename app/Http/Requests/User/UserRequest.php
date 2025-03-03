<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    if ($this->user()->can(['create users', 'edit users'])) {
      return true;
    }
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => ['required', 'string'],
      'avatar' => ['mimes:png,jpg,jpeg,gif', 'max:200'],
      'email' => ['required', 'email', 'unique:users,email'],
      'mobile' => ['required', 'unique:users,mobile'],
      'role_id' => ['required', 'exists:roles,id'],
      'password' => ['required', 'string', 'min:8'],
    ];
  }
}
