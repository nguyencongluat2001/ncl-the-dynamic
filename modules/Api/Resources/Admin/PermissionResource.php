<?php

namespace Modules\Api\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'id'                    => $this->id,
            'cate'                  => $this->cate,
            'permission_group_code' => $this->permission_group_code,
            'user'                  => new UsersResource($this->user),
        ];
    }
}
