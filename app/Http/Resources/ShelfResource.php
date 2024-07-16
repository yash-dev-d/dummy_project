<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShelfResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'books' => BookResource::collection($this->whenLoaded('books')),
        ];
    }
}
