<?php

namespace Modules\Core\Ncl\File\FileServer;

use Modules\Core\Ncl\File\BaseFile;
use Modules\Core\Ncl\LoggerHelpers;
use PDO;

/**
 * Tải lên hoặc Xóa File Server
 * 
 * @author khuongtq
 */
class UploadFileServer extends BaseFile
{

    public function __construct($config, $params)
    {
        parent::__construct();
        $this->init($params);
        $this->setConnection($config);
        $this->logger = new LoggerHelpers();
        $this->logger->setFileName('UploadFileServer');
    }

    /**
     * Tải lên File Server
     * 
     * @return array|false
     */
    public function upload()
    {
        if (!$this->filename || !$this->contentFile || !$this->recordId || !$this->rootDir || !$this->recordCode) return false;
        try {
            $fileNameServer = $this->recordCode . '_' . mt_rand(1000, 1000000) . '.' . $this->getExtension();
            $sql = "EXEC dbo.File_Upload :fileName, :fileNameServer, :stream, :isOnegate, :recordId, :rootDir, :y, :m, :d, :codeTypeFile, :nameTypeFile";
            $query = $this->connection->prepare($sql);
            $query->bindParam(':fileName', $this->filename);
            $query->bindParam(':fileNameServer', $fileNameServer);
            $query->bindParam(':stream', $this->contentFile, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
            $query->bindValue(':isOnegate', $this->isOnegate);
            $query->bindParam(':recordId', $this->recordId);
            $query->bindParam(':rootDir', $this->rootDir);
            $query->bindValue(':y', date('Y'));
            $query->bindValue(':m', date('m'));
            $query->bindValue(':d', date('d'));
            $query->bindParam(':codeTypeFile', $this->codeTypeFile);
            $query->bindParam(':nameTypeFile', $this->nameTypeFile);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);
            // result sample = [
            //     "stream_id" => "18EA2AF5-BFF9-4DDD-BC2A-7CAF4CC1C32C"
            //     "file_id" => "247B77EC-8756-4593-8E8C-047E15D014C7"
            // ]

            return (array) $result[0];
        } catch (\Exception $e) {
            $this->logger->setChannel('UPLOAD')
                ->log('Exception: ' . $e->getMessage(), [
                    'filename'     => $this->filename,
                    'record_id'    => $this->recordId,
                    'rootDir'      => $this->rootDir,
                    'codeTypeFile' => $this->codeTypeFile,
                    'nameTypeFile' => $this->nameTypeFile,
                ]);

            return false;
        }
    }

    /**
     * Lấy extension của file
     * 
     * @return string
     */
    private function getExtension()
    {
        $e = explode('.', $this->filename);
        return end($e);
    }

    /**
     * Xóa khỏi File Server
     * 
     * @return string|false
     */
    public function delete()
    {
        if (!$this->streamId) return false;
        try {
            // $sql = 'EXEC dbo.File_Delete ?, ?';
            // $query = $this->connection->prepare($sql);
            // $query->execute([$this->isOnegate, $this->streamId]);
            $this->connection->exec("EXEC dbo.File_Delete " . $this->isOnegate . ", '" . $this->streamId . "'");
            $this->logger->setChannel('DELETE')
                ->log('Success', ['stream_id' => $this->streamId]);

            return $this->streamId;
        } catch (\Exception $e) {
            $this->logger->setChannel('DELETE')
                ->log('Exception', [
                    'stream_id' => $this->streamId,
                    $e
                ]);

            return false;
        }
    }
}
