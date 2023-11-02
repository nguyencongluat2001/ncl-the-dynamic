<?php

namespace Modules\Core\Efy\FormDynamic;

use Modules\Core\Efy\FormDynamic\ListFormDynamic;
use Modules\Core\Efy\FormDynamic\EFormDynamic;

class FormDynamicFactory {

    public function create($object){
        if($object == "ListFormDynamic"){
            return new ListFormDynamic();
        }else if($object == "EFormDynamic"){
            return new EFormDynamic();
        }
    }

}