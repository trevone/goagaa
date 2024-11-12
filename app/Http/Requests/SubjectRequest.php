<?php

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
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

    $id = $this->route()->subject;

    return [
      'title' => [
        'required',
        'string',
        Rule::unique(Subject::class)->ignore($id)
      ],
      'slug' => [
        'sometimes',
        'nullable',
        'alpha_dash:ascii',
        Rule::unique(Subject::class)->ignore($id)
      ],
    ];
  }
}
