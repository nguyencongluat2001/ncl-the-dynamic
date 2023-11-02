<?php

namespace Modules\Api\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ListTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'   => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'list' => ListResource::collection($this->ListModel),
        ];
    }
}
