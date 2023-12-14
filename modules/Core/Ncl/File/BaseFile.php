<?php

namespace Modules\Core\Ncl\File;

use PDO;
use ReflectionClass;
use ReflectionMethod;
use stdClass;

/**
 * Base File class
 * 
 * @author luatnc
 */
abstract class BaseFile
{


    /**
     * Cấu hình File Server
     */
    protected $config;

    /**
     * Kết nối đến File Server
     */
    protected $connection;

    /**
     * 
     */
    protected $defaultPathUploadLocal = 'attach-file/';

    /**
     * Thư mục lưu file 'attach-file'
     */
    protected $attachPath;

    /**
     * Thư mục file tạm khi upload 'temp_upload'
     */
    protected $tempPath;

    /**
     * Mã file trong $_FILES[mã file]
     */
    protected $fileCode = 'file';

    /**
     * Tên file
     */
    protected $filename;

    /**
     * Stream_id trong FileTable
     */
    protected $streamId;

    /**
     * Content của file
     */
    protected $contentFile;

    /**
     * ID hồ sơ
     */
    protected $recordId;

    /**
     * Mã hồ sơ
     */
    protected $recordCode;

    /**
     * Thư mục gốc (vd 000.00.01.H55)
     */
    protected $rootDir;

    /**
     * Mã tài liệu
     */
    protected $codeTypeFile;

    /**
     * Tên tài liệu
     */
    protected $nameTypeFile;

    /**
     * Upload cho Một cửa hay Dvc
     */
    protected $isOnegate;

    /**
     * Năm lưu file
     */
    protected $year;

    /**
     * Lưu log
     */
    protected $logger;

    /**
     * Steam context cho hàm file_get_contents lấy file local
     */
    protected $contextFileGetContent;

    public function __construct()
    {
        $this->contextFileGetContent = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ]);
    }

    /**
     * Khởi tạo các giá trị truyền vào
     * 
     * @return $this
     */
    protected function init($params)
    {
        $this->filename     = $params['filename'] ?? null;
        $this->streamId     = $params['streamId'] ?? null;
        $this->contentFile  = $params['contentFile'] ?? null;
        $this->recordId     = $params['recordId'] ?? null;
        $this->recordCode   = $params['recordCode'] ?? null;
        $this->rootDir      = $params['rootDir'] ?? null;
        $this->codeTypeFile = $params['codeTypeFile'] ?? null;
        $this->nameTypeFile = $params['nameTypeFile'] ?? null;
        $this->isOnegate    = $params['isOnegate'] ?? 1;
        $this->year         = $params['year'] ?? null;

        return $this;
    }

    /**
     * Kết nối đến File Server hoặc File Local
     * 
     * @param array $config
     * @return $this
     */
    protected function setConnection($config)
    {
        if ($config['type'] == 'FileServer') {
            $this->connectFileServer($config);
        } else {
            $this->connectFileLocal($config);
        }

        return $this;
    }

    /**
     * Kết nối file server
     * 
     * @param array $config
     */
    private function connectFileServer($config)
    {
        $this->connection = new PDO("sqlsrv:Server=" . $config['host'] . ";Database=" . $config['database'], $config['username'], $config['password']);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Kết nối file local
     * 
     * @param array $config
     */
    private function connectFileLocal($config)
    {
        $object = new stdClass();
        $object->urlViewFile = $config['urlViewFile'] ?? '';
        $object->urlSaveFile = $config['urlSaveFile'] ?? '';
        $this->connection = $object;
    }

    /**
     * Convert filesize to to human readable
     * 
     * @param int|float $bytes
     * @param int $decimals
     */
    protected function humanFilesize($bytes, $decimals = 2)
    {
        $bytes = (float) $bytes;
        $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = $bytes > 0 ? floor(log($bytes, 1024)) : 0;

        return number_format($bytes / pow(1024, $factor), $decimals, '.', ',') . ' ' . $size[$factor];
    }
}
