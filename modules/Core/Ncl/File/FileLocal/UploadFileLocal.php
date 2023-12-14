<?php

namespace Modules\Core\Ncl\File\FileLocal;

use Modules\Core\Ncl\File\BaseFile;
use Modules\Core\Ncl\Library;
use Modules\Core\Ncl\LoggerHelpers;

/**
 * Tải lên hoặc Xóa File Local
 * 
 * @author luatnc
 */
class UploadFileLocal extends BaseFile
{

    public function __construct($config, $params)
    {
        parent::__construct();
        $this->init($params);
        $this->setConnection($config);
        $this->logger = new LoggerHelpers();
        $this->logger->setFileName('UploadFileLocal');
        if (!is_dir(public_path('temp_upload'))) mkdir(public_path('temp_upload'));
        if (!is_dir(public_path('attach-file'))) mkdir(public_path('attach-file'));
        $this->attachPath = public_path('attach-file') . chr(92);
        $this->tempPath   = public_path('temp_upload') . chr(92);
    }

    /**
     * Tải lên File Local
     * 
     * @return array
     */
    public function upload()
    {
        $pathLocal        = $this->getAttachPathLocal();
        $fullPath         = $pathLocal['full_path'];
        $path             = $pathLocal['path'];
        $return['status'] = false;
        if ($_FILES[$this->fileCode]['name']) {
            $name       = $_FILES[$this->fileCode]['name'];
            $uploadPath = Library::_createFolder($fullPath, date('Y'), date('m'), date('d'));
            $name       = Library::_convertVNtoEN($name);
            $name       = Library::_replaceBadChar($name);
            $fileName   = date('Y_m_d_His_')  . time() . "!~!" . $name;
            $upload     = move_uploaded_file($_FILES[$this->fileCode]['tmp_name'], $uploadPath . $fileName);
            if ($upload === false) return $return;
            $return['status']    = true;
            $return['file_name'] = $fileName;
            $return['baseUrl']   = url('public/' . $path . date('Y/m/d/') . $fileName);
            $return['basePath']  = $uploadPath . $fileName;
        }

        return $return;
    }

    /**
     * Tải lên File Local
     * 
     * @return array
     */
    public function uploadMulti()
    {
        $pathLocal        = $this->getAttachPathLocal();
        $fullPath         = $pathLocal['full_path'];
        $path             = $pathLocal['path'];
        $return['status'] = false;
        $count            = count($_FILES['file']['name']);
        for ($i = 0; $i < $count; $i++) {
            // if ($_FILES[$this->fileCode]['name']) {
            $name       = $_FILES[$this->fileCode]['name'][$i];
            $uploadPath = Library::_createFolder($fullPath, date('Y'), date('m'), date('d'));
            $name       = Library::_convertVNtoEN($name);
            $name       = Library::_replaceBadChar($name);
            $fileName   = date('Y_m_d_His_')  . time() . "!~!" . $name;
            $upload     = move_uploaded_file($_FILES[$this->fileCode]['tmp_name'][$i], $uploadPath . $fileName);
            if ($upload === false) return $return;
            $return[$i]['file_name'] = $fileName;
            $return[$i]['baseUrl']   = url('public/' . $path . date('Y/m/d/') . $fileName);
            $return[$i]['basePath']  = $uploadPath . $fileName;
        }
        $return['status'] = true;

        return $return;
    }

    /**
     * Xóa khỏi File Local
     * 
     * @return string|false
     */
    public function delete()
    {
        if (!$this->filename) return false;
        $pathLocal                = $this->getAttachPathLocal();
        list($year, $month, $day) = \explode("_", $this->filename);
        $pathFile                 = $pathLocal['full_path'] . $year . chr(92) . $month . chr(92) . $day . chr(92) . $this->filename;
        if (is_file($pathFile)) {
            $delete = unlink(realpath($pathFile));
            $this->logger->setChannel('DELETE')->log($this->filename, [$delete, $pathFile]);
            if ($delete) return $this->filename;
        }

        return false;
    }

    /**
     * Tải file vào thư mục tạm temp_upload
     * 
     * @return array
     */
    public function uploadTemp()
    {
        $return['status'] = false;
        if ($_FILES[$this->fileCode]['name']) {
            $name     = $_FILES[$this->fileCode]['name'];
            $path     = $this->tempPath;
            $name     = Library::_convertVNtoEN($name);
            $name     = Library::_replaceBadChar($name);
            $fileName = date('Y_m_d_His') . time() . "!~!" . $name;
            $upload   = move_uploaded_file($_FILES[$this->fileCode]['tmp_name'], $path . $fileName);
            if ($upload === false) return $return;
            $return['status']    = true;
            $return['file_name'] = $fileName;
            $return['baseUrl']   = url('public/temp_upload/' . $fileName);
            $return['basePath']  = $path . $fileName;
        }

        return $return;
    }

    /**
     * Đẩy nhiều file từ client lên thư mục temp
     * 
     * @return array $return Thông tin trả về thành công của file
     */
    public function uploadMultiTemp()
    {
        $count            = count($_FILES[$this->fileCode]['name']);
        $return['status'] = false;
        for ($i = 0; $i < $count; $i++) {
            // foreach ($_FILES as $file) {
            $name     = $_FILES[$this->fileCode]['name'][$i];
            $path     = $this->tempPath;
            $name     = Library::_convertVNtoEN($name);
            $name     = Library::_replaceBadChar($name);
            $fileName = date('Y_m_d_His') . time() . "!~!" . $name;
            $upload   = move_uploaded_file($_FILES[$this->fileCode]['tmp_name'][$i], $path . $fileName);
            if ($upload === false) return $return;
            $return[$i]['file_name'] = $fileName;
            $return[$i]['baseUrl']   = url('public/temp_upload/' . $fileName);
            $return[$i]['basePath']  = $path . $fileName;
        }
        $return['status'] = true;

        return $return;
    }

    /**
     * Xóa file trong thư mục tạm temp_upload
     * 
     * @return string|false
     */
    public function deleteTemp()
    {
        if (!$this->filename) return false;
        $pathFile = $this->tempPath . $this->filename;
        if (is_file($pathFile)) {
            $delete = unlink(realpath($pathFile));
            if ($delete) return $this->filename;
        }

        return false;
    }

    /**
     * Lấy đường dẫn attach-file
     * 
     * @return array
     */
    private function getAttachPathLocal()
    {
        $fullPath = $this->attachPath;
        $path     = $this->defaultPathUploadLocal;
        if ($this->rootDir != '') {
            $fullPath .= $this->rootDir . chr(92);
            $path     .= $this->rootDir . '/';
            if (!is_dir($fullPath)) mkdir($fullPath);
        }

        return [
            'path'      => $path,
            'full_path' => $fullPath,
        ];
    }
}
