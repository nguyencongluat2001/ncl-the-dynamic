<?php

namespace Modules\Api\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
            'department_id' => $this->id,
            'units_id'      => $this->units_id,
            'code'          => $this->code,
            'name'          => $this->name,
            'address'       => $this->address,
            'owner_code'    => $this->owner_code,
            'owner_ward'    => $this->owner_ward,
            'type_group'    => $this->type_group,
            'parent_name'   => isset($this->parentName->name) ? $this->parentName->name : '',
        ];
    }
}
