<?php

namespace Modules\Api\Resources\Admin;

use Modules\Core\Ncl\Http\Resource\BaseResource;

class AuthResource extends  BaseResource
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
            'access_token' => $this->token,
            'user' => [
                'id'   => $this->id,
                'code' => $this->position->code,
                'name' => $this->name,
            ],
            'unit' => [
                'department_id' => $this->unit->id,
                'units_id'      => $this->unit->units_id,
                'code'          => $this->unit->code,
                'name'          => $this->unit->name,
                'address'       => $this->unit->address,
                'owner_name'    => $this->unit->owner_name,
                'owner_code'    => $this->unit->owner_code,
                'owner_ward'    => $this->unit->owner_ward,
            ],
            'menu' => $this->menus,
            'hdsd' => $this->hdsd,
        ];
    }
}
