<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => asset(Storage::disk('local')->url($this->path)),
            'thumbnail' => asset(Storage::disk('local')->url($this->thumbnail_path)),
            'isImage' => $this->mime_type == 'image/jpeg' || $this->mime_type == 'image/png' ? true : false,
            'isMaxSize' => ($this->size / 1024) / 1024 > 10 ? true : false
        ];
    }
}
