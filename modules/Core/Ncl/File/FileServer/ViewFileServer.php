<?php

namespace Modules\Core\Ncl\File\FileServer;

use Modules\Core\Ncl\File\BaseFile;
use Modules\Core\Ncl\Library;
use PDO;

/**
 * Xem hoặc Lấy thông tin File Server
 * 
 * @author luatnc
 */
class ViewFileServer extends BaseFile
{

    public function __construct($config, $params)
    {
        parent::__construct();
        $this->init($params);
        $this->setConnection($config);
        $this->config = $config;
    }

    /**
     * Xem file
     * 
     * @return Response|false
     */
    public function view()
    {
        $sql = "SELECT TOP 1 [name], [file_stream], [file_type] FROM dbo.FileTableTb";
        if ($this->streamId) {
            $sql .= " WHERE [stream_id] = ?";
            $execValue = $this->streamId;
        } else if ($this->filename) {
            $sql .= " WHERE [name] = ?";
            $execValue = $this->filename;
        } else return false;
        $query = $this->connection->prepare($sql);
        $query->execute([$execValue]);
        // $query->bindColumn('file_stream', $lob, PDO::PARAM_LOB);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        if (!$result) return false;
        $mime = Library::mimeContentType($result[0]->file_type);
        $expFilename = explode('!~!', $result[0]->name);

        return response($result[0]->file_stream, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . end($expFilename) . '"',
        ]);
    }

    /**
     * Lấy url view file
     * 
     * @return string|false
     */
    public function url()
    {
        $url = $this->config['urlViewFile'];
        if (empty($url)) return false;
        $sql = "SELECT TOP 1 [stream_id], [name] FROM dbo.FileTableTb";
        if ($this->streamId) {
            $sql .= " WHERE [stream_id] = ?";
            $execValue = $this->streamId;
        } else if ($this->filename) {
            $sql .= " WHERE [name] = ?";
            $execValue = $this->filename;
            if (!$this->year) $this->year = explode('_', $this->filename)[0];
        } else return false;
        $query = $this->connection->prepare($sql);
        $query->execute([$execValue]);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        if (!$result) return false;

        return $url . $result[0]->stream_id . ($this->year ? '?y=' . $this->year : '');
    }

    /**
     * Thông tin File Server
     * 
     * @return array|object|false
     */
    public function info()
    {
        $sql = "SELECT TOP 1 [stream_id], [name], [file_type], [cached_file_size], [creation_time]
                FROM dbo.FileTableTb";
        if ($this->streamId) {
            $sql .= " WHERE [stream_id] = ?";
            $execValue = $this->streamId;
        } else if ($this->filename) {
            $sql .= " WHERE [name] = ?";
            $execValue = $this->filename;
        } else return false;
        $query = $this->connection->prepare($sql);
        $query->execute([$execValue]);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        if (!$result) return false;
        $expFilename = explode('!~!', $result[0]->name);
        $result[0]->mime_content_file = Library::mimeContentType($result[0]->file_type);
        $result[0]->real_name = end($expFilename);
        $result[0]->filesize = $this->humanFilesize($result[0]->cached_file_size);

        return $result[0];
    }
}
