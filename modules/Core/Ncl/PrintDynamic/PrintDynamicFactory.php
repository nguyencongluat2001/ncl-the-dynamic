<?php

namespace Modules\Core\Ncl\PrintDynamic;

use Modules\Core\Ncl\PrintDynamic\WordPrintDynamic;
use Modules\Core\Ncl\PrintDynamic\PdfPrintDynamic;
use Modules\Core\Ncl\PrintDynamic\ExcelPrintDynamic;

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
