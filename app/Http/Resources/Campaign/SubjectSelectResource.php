<?php

namespace App\Http\Resources\Subject;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectSelectResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    // return parent::toArray($request);

    // dd($this);

    return [
      'id' => $this->id,
      'title' => $this->title,
      'slug' => $this->slug,
      'disabled' => $this->disabled ?? false,
      'img_url' => 'https://wsh.fra1.cdn.digitaloceanspaces.com/' . ($this->img_path ?? 'trt'),
    ];
  }
}
