<?php

namespace Modules\System\Cms\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\EFY\Library;
use Illuminate\Http\Request;
use DB;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author Toanph <skype: toanph155>
 */
class FileController extends Controller {

    /**
     * Lay case thu muc doi tuong cap 1
     * @param Request $request
     * @return jSon
     */
    public function index(Request $request) {
        $arrInput = $request->input();

        $input_path = base_path('public\ckeditor_file');
//        $arrDir = scandir($input_path);
        $arrDir = $this->sort_dir_files($input_path);
        $urlPath = url('') . str_replace(base_path(''), '', $input_path);
        $urlPath = str_replace('\\', '/', $urlPath);
        $data['arrDir'] = $arrDir;
        $data['urlPath'] = $urlPath;
        $data['objJS'] = $arrInput['objJS'];
        $data['input_path'] = $input_path;
        return view('Cms::file.add', $data);
    }

    public function getAllFolderInPath(Request $request) {
        $arrInput = $request->input();
        $input_path = $arrInput['input_path'];
        $input_path = base64_decode($input_path);
        $arrDir = $this->sort_dir_files($input_path);
        $urlPath = url('') . str_replace(base_path(''), '', $input_path);
        $urlPath = str_replace('\\', '/', $urlPath);
        $data['arrDir'] = $arrDir;
        $data['urlPath'] = $urlPath;
        $data['objJS'] = $arrInput['objJS'];
        $data['input_path'] = $input_path;
        return view('Cms::file.folder_by_path', $data);
    }

    public function sort_dir_files($dir) {
        $sortedData = array();
        foreach (scandir($dir) as $file) {
            if (is_file($dir . '/' . $file))
                array_push($sortedData, $file);
            else
                array_unshift($sortedData, $file);
        }
        return $sortedData;
    }

}
