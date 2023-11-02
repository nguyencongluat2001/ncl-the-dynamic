<?php

namespace Modules\Core\Console;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Console\DataUserImport;


class ConvertUserDataExcel {
    public function convert(){
        $path = storage_path('DATA_IMPORT/ds_tai_khoan.xlsx');
        Excel::import(new DataUserImport, $path);
        return back();
    }
}