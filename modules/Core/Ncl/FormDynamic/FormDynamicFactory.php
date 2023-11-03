<?php

namespace Modules\Core\Ncl\FormDynamic;

use Modules\Core\Ncl\FormDynamic\ListFormDynamic;
use Modules\Core\Ncl\FormDynamic\EFormDynamic;

class FormDynamicFactory {

    public function create($object){
        if($object == "ListFormDynamic"){
            return new ListFormDynamic();
        }else if($object == "EFormDynamic"){
            return new EFormDynamic();
        }
    }

}