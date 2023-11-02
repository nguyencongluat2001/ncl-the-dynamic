<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AnswersModel extends Model {
    protected $table = 'CMS_ANSWERS';
    protected $primaryKey = 'PK_CMS_ANSWER';
    public $incrementing = false;
    public $timestamps = false;

    public function questions()
    {
        return $this->belongsTo('Modules\System\Cms\Models\QuestionsModel', 'FK_QUESTION');
    }
}
