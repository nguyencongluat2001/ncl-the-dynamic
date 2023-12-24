<?php

namespace Modules\Frontend\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class HomeModel extends Model
{
    protected $table = 'users';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'address',
        'email',
        'mobile',
        'fax',
        'username',
        'password',
        'order',
        'status',
        'role',
        'sex',
        'birthday',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * @param mixed $query
     * @param mixed $param
     * @param mixed $value
     *
     * @return Query
     */
    public function _getAll($input)
    {
        $query = $this->query();
        Paginator::currentPageResolver(function() use ($input) {
            return $input['offset'];
        });
        if($input['txt_search']){
            $query->where('name', 'LIKE','%' . $input['txt_search'] .'%')->orWhere('username', 'LIKE','%' . $input['txt_search'] .'%');
        }
        return $query->orderBy('order', 'desc')->paginate($input['limit']);
    }
}
