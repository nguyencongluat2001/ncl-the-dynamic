<?php

namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model động để set bất cứ table nào vào
 * 
 * - set table: setTable()
 * - set connection: setConnection()
 * - set primary key: setKeyName()
 * - set key type: setKeyType()
 * - set icrementing: setIncrementing()
 * - set fillable: fillable()
 * - set sortable: setSortable()
 * - set per page: setPerPage()
 * 
 * @author luatnc
 */
class DynamicModel extends Model
{
    protected $keyType = 'string';

    /**
     * Set the sortable for the model
     * 
     * @param array $sortable
     * @return $this
     */
    public function setSortable(array $sortable)
    {
        $this->sortable = $sortable;

        return $this;
    }
}
