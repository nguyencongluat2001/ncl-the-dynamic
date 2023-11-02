<?php

namespace Modules\Api\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
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
            'id'                 => $this->id,
            'code'               => $this->code,
            'name'               => $this->name,
            'factor'             => $this->factor,
            'system_listtype_id' => $this->system_listtype_id,
        ];
    }
}
