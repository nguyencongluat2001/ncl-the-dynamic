<?php

namespace Modules\Api\Resources\Admin;

use Modules\Core\Ncl\Http\Resource\BaseResource;

class MenuResource extends  BaseResource
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
            'id'    => $this->id,
            'url'   => $this->url,
            'name'  => $this->name,
            'icon'  => $this->icon,
            'child' => $this->child,
        ];
    }
}
