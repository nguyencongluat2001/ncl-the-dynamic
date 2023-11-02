<?php

namespace Modules\System\Cms\Repositories;

use Modules\Core\Efy\Http\BaseRepository;
use Modules\System\Cms\Models\FileModel;

class FileRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return FileModel::class;
    }
}