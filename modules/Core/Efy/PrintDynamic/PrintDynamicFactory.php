<?php

namespace Modules\Core\Efy\PrintDynamic;

use Modules\Core\Efy\PrintDynamic\WordPrintDynamic;
use Modules\Core\Efy\PrintDynamic\PdfPrintDynamic;
use Modules\Core\Efy\PrintDynamic\ExcelPrintDynamic;

class PrintDynamicFactory {

    public function create($object){
        if($object == "WordPrintDynamic"){
            return new WordPrintDynamic();
        }else if($object == "HtmlPrintDynamic"){
            return new HtmlPrintDynamic();
        }else if($object == "PdfPrintDynamic"){
            return new PdfPrintDynamic();
        }else if($object == "ExcelPrintDynamic"){
            return new ExcelPrintDynamic();
        }
    }
}
