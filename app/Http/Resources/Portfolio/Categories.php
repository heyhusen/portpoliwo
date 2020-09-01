<?php

namespace App\Http\Resources\Portfolio;

use App\Http\Resources\Portfolio\Works;
use Illuminate\Http\Resources\Json\JsonResource;

class Categories extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'works' => Works::collection($this->works),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
