<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserSharingRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    if ($this->user()->can(['edit users'])) {
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
      'user_id' => ['required', 'exists:users,id'],
      'vertical_id' => ['required', 'exists:verticals,id'],
      'admission_session_id' => ['required', 'exists:admission_sessions,id'],
      'start_date' => ['required', 'date'],
      'scheme_ids' => ['required', 'array', 'distinct'],
      'scheme_ids.*' => ['required', 'exists:schemes,id'],
      'fee_structure_ids' => ['required', 'array', 'distinct'],
      'fee_structure_ids.*' => ['required', 'exists:fee_structures,id'],
    ];
  }
}
