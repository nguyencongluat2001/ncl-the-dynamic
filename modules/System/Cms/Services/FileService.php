<?php

namespace Modules\System\Cms\Services;

use Modules\Core\Efy\Http\BaseService;
use Modules\Core\Efy\Library;
use Modules\System\Cms\Repositories\FileRepository;

class FileService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }
    public function repository()
    {
        return FileRepository::class;
    }
    public function upload($file = [])
    {
        $file = $file != [] ? $file : ($_FILES ?? []);
        $return = [];
        $sDir = public_path('attach-file') . chr(92);
        $filepath   = Library::_createFolder($sDir, date('Y'), date('m'), date('d'));
        if($file != []){
            $filename     = $file['name'];
            $filename     = Library::_convertVNtoEN($filename);
            $filename     = Library::_replaceBadChar($filename);
            $filename     = date("Y_m_d_His") . rand(1000, 9999) . '!~!' . $filename;
            $fullfilename = $filepath . '/' . $filename;
            copy($file['tmp_name'], $fullfilename);
            $folderUnlink = date('Y') . '/' . date('m') . '/' . date('d') . '/' . date('Y_m_d_Ymd');
            if (file_exists(public_path('temp_upload') . '/' . $folderUnlink . '/' . $file['name'])) {
                unlink(public_path('temp_upload') . '/' . $folderUnlink . '/' . $file['name']);    // xóa file cũ
            }
            $return = [
                'file_name' => $filename,
                'baseUrl' =>  [
                    'url' => url('public/attach-file') . '/' . date('Y/m/d') . '/' . $filename,
                    'fileName' => $filename,
                ],
            ];
        }
        return $return;
    }
}