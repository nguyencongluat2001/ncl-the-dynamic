<?php

namespace Modules\Api\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            //'access_token' => $this->when($this->whenLoaded($this->token), $this->token),
            'id'       => $this->id,
            'code'     => $this->position->code,
            'name'     => $this->name,
            'units_id' => new UnitResource($this->unit),
            'position' => new PositionResource($this->position),
            //'unit' => new UnitResource($this->unit),
        ];
    }
}
