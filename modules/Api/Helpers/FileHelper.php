<?php

namespace Modules\Api\Helpers;

use Modules\Api\Models\Portal\FilesModel;
use Modules\Api\Services\FileService;
use Modules\Core\Ncl\File\FileFactory;
use Modules\Core\Ncl\FileServerPDO;
use Modules\Core\Ncl\Library;
use Modules\Core\Ncl\LoggerHelpers;

/**
 * Xử lý file
 * 
 * @updated 05/05/2023
 */
class FileHelper
{

    private $fileServer;
    private $fileService;
    private $sDirTemp;
    private $sDirSave;
    private $sDirSaveOrigin;
    public $fileCode = 'file';
    private $context;
    private $logger;
    private $unit = '';

    public function __construct()
    {
        $this->fileServer = new FileServerPDO;
        $this->fileService = new FileService();
        $this->logger = new LoggerHelpers();
        $this->logger->setFileName('LogSystem_Api_FileHelper');
        $this->context = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ]);
        if (!is_dir(public_path("temp_upload"))) mkdir(public_path("temp_upload"));
        if (!is_dir(public_path("attach-file"))) mkdir(public_path("attach-file"));
        $this->sDirSaveOrigin = public_path("attach-file") . chr(92);
        $this->sDirTemp = public_path("temp_upload") . chr(92);
        $this->sDirSave = $this->sDirSaveOrigin;
    }

    /**
     * Tải file lên FILE SERVER
     * 
     * @param string $filename
     * @param string $recordId
     * @param string $ownerCode
     * @param string $contentFile
     * @param string $codeRecordtypefile
     * @param string $nameRecordtypefile
     * @return array|false
     */
    public function upload($filename, $recordId, $recordCode, $ownerCode, $contentFile, $codeRecordtypefile = '', $nameRecordtypefile = '')
    {
        $params = [
            'filename'     => $filename,
            'contentFile'  => $contentFile,
            'recordId'     => $recordId,
            'recordCode'   => $recordCode,
            'rootDir'      => $ownerCode,
            'codeTypeFile' => $codeRecordtypefile,
            'nameTypeFile' => $nameRecordtypefile,
        ];

        return FileFactory::load(FileFactory::UPLOAD_FILE, $params)->upload();
    }

    /**
     * Xoá file trong File Server
     * 
     * @param string $streamId Stream_id của file server
     * @return string|false
     */
    public function delete($streamId)
    {
        return FileFactory::load(FileFactory::UPLOAD_FILE, ['streamId' => $streamId])->delete();
    }

    /**
     * Lấy url file
     * 
     * @param string $filename
     * @return string|false
     */
    public function url($filename, $streamId = '', $ownerCode = '', $year = '')
    {
        return FileFactory::load(FileFactory::VIEW_FILE, [
            'filename' => $filename,
            'streamId' => $streamId,
            'rootDir'  => $ownerCode,
            'year'     => $year,
        ])->url();
    }

    /**
     * View file
     * 
     * @param string $filename
     * @return string|false
     */
    public function view($filename, $ownerCode = '', $year = '')
    {
        return FileFactory::load(FileFactory::VIEW_FILE, ['filename' => $filename, 'rootDir' => $ownerCode, 'year' => $year])->view();
    }


    /* ---------------------------------------------------------------------  */


    /**
     * Mặt định thư mục mặt định là mã đơn vị vào thư mục ổ cứng máy chủ
     * 
     * @param string $unit: mã đơn vị cần cập nhật vd: ....28.H55
     * @return void
     */
    public function setAttachFile($unit)
    {
        $this->sDirSave = $this->sDirSaveOrigin . $unit . chr(92);
        $this->unit     = $unit;
        if (!is_dir($this->sDirSave)) mkdir($this->sDirSave);
    }

    /**
     * Tải file vào thư mục tạm temp_upload
     * 
     * @return array
     */
    public function uploadFileTemp()
    {
        $return['status'] = false;
        if ($_FILES[$this->fileCode]['name']) {
            $fileName      = $_FILES[$this->fileCode]['name'];
            $path          = $this->sDirTemp;
            $fileName      = Library::_convertVNtoEN($fileName);
            $fileName      = Library::_replaceBadChar($fileName);
            $sFullFileName = date('Y_m_d_His')  . mt_rand(1, 1000000) . "!~!" . $fileName;
            move_uploaded_file($_FILES[$this->fileCode]['tmp_name'], $path . $sFullFileName);
            $return['status']    = true;
            $return['file_name'] = $sFullFileName;
            $return['baseUrl']   = [
                'url'      => url("public/temp_upload/$sFullFileName"),
                'fileName' => $fileName,
            ];
        }

        return $return;
    }

    /**
     * Đẩy nhiều file từ client lên thư mục temp
     * 
     * @return array $return Thông tin trả về thành công của file
     */
    public function uploadMuiltiFileTemp()
    {
        $i = 0;
        $return = [];
        foreach ($_FILES as $file) {
            $fileName      = $file['name'];
            $path          = $this->sDirTemp;
            $fileName      = Library::_convertVNtoEN($fileName);
            $fileName      = Library::_replaceBadChar($fileName);
            $sFullFileName = date('Y_m_d_His') . mt_rand(1, 1000000) . "!~!" . $fileName;
            move_uploaded_file($file['tmp_name'], $path . $sFullFileName);
            $return[$i]['file_name'] = $sFullFileName;
            $return[$i]['baseUrl']   = [
                'url'      => url("public/temp_upload/$sFullFileName"),
                'fileName' => $fileName,
            ];
            $i++;
        }

        return $return;
    }

    /**
     * Xóa file trong temp_upload
     * 
     * @param string $filename
     */
    public function removeFileTemp($filename)
    {
        // Kiểm tra xem file có trên ổ cứng không
        $pathFile = $this->sDirTemp . "\\" . $filename;
        if (file_exists($pathFile)) {
            unlink(realpath($pathFile));
        }
    }

    /**
     * Chuyển file đính kèm vào thư mục / mã đơn vị / năm / tháng / ngày
     * 
     * @param string $filename tên file. vd: 2022_07_29_1007000000994897!~!Quyet dinh 01.pdf
     * @param string $unit: Mã đơn vị
     * @return ProcessService $this
     */
    // public function moveFileFromTemp($filename, $unit = '')
    // {
    //     // Kiểm tra xem file có trên ổ cứng không
    //     $spathFile = $this->sDirTemp . $filename;
    //     if (is_file($spathFile)) {
    //         $newFile = Library::_createFolder($this->sDirSave, date('Y'), date('m'), date('d')) . $filename;
    //         copy($spathFile, $newFile);
    //         unlink(realpath($spathFile));

    //         return $newFile;
    //     }

    //     return null;
    // }

    /**
     * Chuyển file thành phần hồ sơ của hồ sơ qua mạng vào attach-file
     */
    public function moveFileFromRecordNet($fileName, $content)
    {
        $arrData = \explode("_", $fileName);
        if (isset($arrData[0]) && isset($arrData[1]) && isset($arrData[2])) {
            $newFile = Library::_createFolder($this->sDirSave, $arrData[0], $arrData[1], $arrData[2]) . $fileName;
            file_put_contents($newFile, $content);
        }
    }

    /**
     * Xóa file trong attach-file
     */
    public function removeFileAttach($filename)
    {
        $arrFile = \explode("_", $filename);
        // Kiểm tra xem file có trên ổ cứng không
        $pathFile = $this->sDirSave . $arrFile[0] . "\\" . $arrFile[1] . "\\" . $arrFile[2] . "\\" . $filename;
        if (file_exists($pathFile)) {
            $remove = unlink(realpath($pathFile));
            $this->logger->setChannel('removeFileAttach')->log($filename, [$remove, $pathFile]);
        }
    }

    /**
     * Lấy url và tên file trong $folder
     */
    public function getUrlFileByName($filename, $folder = 'attach-file', $unit = '', $registorId = '')
    {
        $url = $realFileName = '';
        $attachFolder = $folder;
        if (
            $unit == ""
            && isset(auth('sanctum')->user()->owner_code)
            && auth('sanctum')->user()->owner_code !== ""
        ) {
            $unit = auth('sanctum')->user()->owner_code;
        }
        if ($unit != '') $attachFolder = $folder . '/' . $unit;
        $arrFile = \explode("_", $filename);
        if (isset($arrFile[0]) && isset($arrFile[1]) && isset($arrFile[2]) && isset($arrFile[3])) {
            $arrFilename = \explode("!~!", $arrFile[3]);
            if (isset($arrFilename[1])) {
                if ($folder == 'attach-file') {
                    $checkPath = $arrFile[0] . "/" . $arrFile[1] . "/" . $arrFile[2] . "/" . $filename;
                } else {
                    $checkPath = $filename;
                }
                // Kiem tra xem file co ton tai khong o cung hay khong
                if (file_exists(public_path($attachFolder . '/' . $checkPath))) {
                    $url             = url('public/' . $attachFolder . '/' . $checkPath);
                    $arrRealFileName = explode("!~!", $filename);
                    $realFileName    = $arrRealFileName[1];
                } else {
                    // Kiem tra co file da ky khong
                    // $arrRealFileName = explode("!~!", $filename);
                    // $pos = strrpos($arrRealFileName[1], '.');
                    // $realName = substr($arrRealFileName[1], 0, $pos) . '.signed' . substr($arrRealFileName[1], $pos);
                    // $position = strrpos($checkPath, '.');
                    // $checkPath = substr($checkPath, 0, $position) . '.signed' . substr($checkPath, $position);
                    // if (is_file(public_path($attachFolder . '/' . $checkPath))) {
                    //     $url = url('public/' . $attachFolder . '/' . $checkPath);
                    //     $realFileName = $realName;
                    // }
                    // Kiem tra thu muc dung chung
                    // else 
                    if (file_exists(public_path($folder . '/' . $checkPath))) {
                        $url             = url('public/' . $folder . '/' . $checkPath);
                        $arrRealFileName = explode("!~!", $filename);
                        $realFileName    = $arrRealFileName[1];
                    } elseif (
                        // kiểm tra trong số hóa
                        $registorId != ''
                        && $citizen = \DB::connection('sqlsrv')->table('citizen_file')
                        ->where('citizen_code', $registorId)
                        ->where('file_name', $filename)
                        ->first()
                    ) {
                        $attachFolder = $folder . '/' . $citizen->owner_code . '/' . $checkPath;
                        if (file_exists(public_path($attachFolder))) {
                            $url             = url('public/' . $attachFolder);
                            $arrRealFileName = explode("!~!", $filename);
                            $realFileName    = $arrRealFileName[1];
                        }
                    } else {
                        // Kiem tra may chu cu
                        $url             = "http://file.motcuadientu.vn/thainguyen/general/public/attach-file/" . $unit . "/" . $checkPath;
                        $arrRealFileName = explode("!~!", $filename);
                        $realFileName    = $arrRealFileName[1];
                    }
                }
            }
        }
        return [
            'url'      => $url,
            'fileName' => $realFileName
        ];
    }

    /**
     * Lấy url và tên file từ portal
     * 
     * @param string $filename Tên file (có prefix ngày tháng năm)
     * @return array
     */
    public function getUrlFilePortal($filename, $idRecord = '')
    {
        try {
            $url = $realFileName = $urlSaveFile = '';
            if ($filename !== '') {
                $arrfile      = explode('!~!', $filename);
                $realFileName = $arrfile[1];
                $arrFolder    = explode('_', $arrfile[0]);
                $folder       = $arrFolder[0] . chr(47) . $arrFolder[1] . chr(47) . $arrFolder[2];
                $url          = config('internal.portal.url_view_file', 'https://dichvucong.thainguyen.gov.vn/portal') . '/public/attach-file/' . $folder . chr(47) . $filename;
                $urlSaveFile = config('internal.portal.url', 'http://10.134.253.129/portal') . '/public/attach-file/' . $folder . chr(47) . $filename;
                if ($idRecord != '') {
                    $filePortal = FilesModel::where('net_record_id', $idRecord)->where('filename', $filename)->first();
                    if ($filePortal && !empty($filePortal->url)) {
                        $url = $filePortal->url;
                    }
                }
            }

            return [
                'url'           => $url,
                'url_save_file' => $urlSaveFile,
                'fileName'      => $realFileName,
            ];
        } catch (\Exception $e) {
            $this->logger->setChannel('Function: getUrlFilePortal')
                ->log(
                    'Lỗi lấy url file từ portal',
                    ['filename' => $filename, 'exception' => $e->getMessage()]
                );
            return [
                'url'           => '',
                'url_save_file' => '',
                'fileName'      => '',
            ];
        }
    }

    /**
     * Lấy base64 content file dịch vụ công
     * 
     * @param string $url
     * @return string
     */
    public function getFileContentPortal($url)
    {
        return base64_encode(file_get_contents($url, false, $this->context));
    }

    /**
     * Tải file từ url
     * 
     * @param string $filename Tên file (có prefix)
     * @param string $urlFile Url file cần tải về
     * 
     * @return array
     */
    // public function saveAttachFileFromUrl($filename, $urlFile)
    // {
    //     try {
    //         $url = $realFileName = '';
    //         if ($filename) {
    //             $arrfile      = explode('!~!', $filename);
    //             $realFileName = $arrfile[1];
    //             $arrFolder    = explode('_', $arrfile[0]);
    //             $folder       = $arrFolder[0] . chr(92) . $arrFolder[1] . chr(92) . $arrFolder[2];
    //             $checkPath    = $folder . chr(92) . $filename;
    //             $attachFolder = 'attach-file';
    //             if ($this->unit && $this->unit !== '')
    //                 $attachFolder = $attachFolder . chr(92) . $this->unit;
    //             $url = url('public' . chr(92) . $attachFolder . chr(92) . $checkPath);
    //             if (!file_exists($this->sDirSave . $checkPath)) {
    //                 $newPath = Library::_createFolder($this->sDirSave, $arrFolder[0], $arrFolder[1], $arrFolder[2]) . $filename;
    //                 $content = file_get_contents($urlFile, false, $this->context);
    //                 file_put_contents($newPath, $content);
    //                 $this->logger->setChannel('saveAttachFileFromUrl')->log($filename, $urlFile);
    //             } else {
    //                 $this->logger->setChannel('saveAttachFileFromUrl')
    //                     ->log('File already exists', ['path' => $this->sDirSave . $checkPath]);
    //             }
    //         }

    //         return [
    //             'url'      => $url,
    //             'fileName' => $realFileName,
    //         ];
    //     } catch (\Exception $e) {
    //         $this->logger->setChannel('saveAttachFileFromUrl')
    //             ->log('Exception: ' . $e->getMessage(), ['filename' => $filename, 'url_file' => $urlFile]);
    //         return [
    //             'url'      => '',
    //             'fileName' => '',
    //         ];
    //     }
    // }

    public function getUrlFileOther($filename, $unit, $folder = 'attach-file')
    {
        $url = $realFileName = '';
        if ($filename !== '') {
            $attachFolder = $folder . '/' . $unit;
            $arrfile      = explode('!~!', $filename);
            $realFileName = $arrfile[1];
            $arrFolder    = explode('_', $arrfile[0]);
            $folder       = $arrFolder[0] . chr(92) . $arrFolder[1] . chr(92) . $arrFolder[2];
            $url          = url('public/' . $attachFolder . '/' . $folder . chr(92) . $filename);
        }

        return [
            'url'          => $url,
            'fileName'     => $realFileName,
            'fullFileName' => $filename
        ];
    }

    /**
     * Lấy thông tin file
     */
    // public function fileInfo(string $filename): array
    // {
    //     if ($filename) {
    //         $arrFolder = explode('_', explode('!~!', $filename)[0]);
    //         $p         = $this->sDirSave . $arrFolder[0] . chr(92) . $arrFolder[1] . chr(92) . $arrFolder[2] . chr(92) . $filename;
    //         if ($return['exists'] = is_file($p)) {
    //             $return             = array_merge($return, pathinfo($p));
    //             $return['filesize'] = @filesize($p);
    //         }

    //         return $return;
    //     }
    // }


    /*
    |---------------------------------------------------------------------------
    | File Server
    |---------------------------------------------------------------------------
    */


    /**
     * Upload file to File Server
     */
    // public function uploadFileServer($filename, $recordId, $ownerCode, $codeRecordtypefile, $nameRecordtypefile, $contentFile = null)
    // {
    //     $ownerCode = getOwnerCode($ownerCode);
    //     $pathfile = null;
    //     $result = false;
    //     // Nếu không có file content thì lấy trong thư mục tạm
    //     if ($contentFile == null) {
    //         $pathfile = $this->sDirTemp . $filename;
    //         if (is_file($pathfile)) {
    //             // có thể dùng 1 trong 2 func fopen() và file_get_contents()
    //             // $contentFile = fopen($pathfile, "rb"); 
    //             $contentFile = file_get_contents($pathfile);
    //         }
    //     }
    //     // Nếu tồn tại file content -> upload
    //     if (!$contentFile) return false;
    //     $result = $this->fileServer->uploadFile($filename, $contentFile, $recordId, $ownerCode, $codeRecordtypefile, $nameRecordtypefile);
    //     // Nếu file lấy từ path và up lên thành công -> xoá file vật lý
    //     if ($pathfile && $result) unlink(realpath($pathfile));

    //     return $result;
    // }

    /**
     * Xoá file trong File Server
     */
    public function deleteFileServer(string $filestreamId)
    {
        return $this->fileServer->deleteFile($filestreamId);
    }

    /**
     * Tạo url mở file server
     * 
     * @param string $filestreamId ID file server
     * @param string $filename Tên file (có tiền tố Y_m_d)
     * @return array
     */
    public function getUrlFileServer(string $filestreamId, string $filename): array
    {
        if (empty($filestreamId)) {
            return [
                'url'      => '',
                'fileName' => '',
            ];
        }
        $name = explode('!~!', $filename);
        return [
            'url'      => url('open-file/' . $filestreamId),
            'fileName' => end($name),
        ];
    }

    /**
     * Select SQL thô từ DB FileServer
     */
    // public function rawSqlFileServer(string $sql)
    // {
    //     return $this->fileServer->rawSQL($sql);
    // }
}
