<?php

namespace Modules\Core\Ncl\File\FileLocal;

use Modules\Core\Ncl\File\BaseFile;
use stdClass;

/**
 * Xem hoặc Lấy thông tin File Local
 * 
 * @author khuongtq
 */
class ViewFileLocal extends BaseFile
{

    public function __construct($config, $params)
    {
        parent::__construct();
        $this->init($params);
        $this->setConnection($config);
    }

    /**
     * Xem file
     * 
     * @return Response|false
     */
    public function view()
    {
        if (!$this->filename) return false;
        $url = $this->connection->urlViewFile;
        list($year, $month, $day) = explode('_', $this->filename);
        $expFilename = explode('!~!', $this->filename);
        $path = '' . ($this->rootDir != '' ? $this->rootDir . '/' : '') . $year . '/' . $month . '/' . $day . '/' . $this->filename;
        $fullPath = str_replace(' ', '%20', $url . $path);
        $content = @file_get_contents($fullPath, false, $this->contextFileGetContent);
        if ($content === false) return false;
        $mime = mime_content_type(public_path('attach-file/' . $path));

        return response($content, 200, [
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
        if (!$this->filename) return false;
        $url = $this->connection->urlViewFile;
        list($year, $month, $day) = explode('_', $this->filename);
        $path = '' . ($this->rootDir != '' ? $this->rootDir . '/' : '') . $year . '/' . $month . '/' . $day . '/' . $this->filename;
        $fullPath = str_replace(' ', '%20', $url . $path);
        // $content = @fopen($fullPath, 'r', false, $this->contextFileGetContent);
        // if ($content === false) return false;

        return $fullPath;
    }

    /**
     * Thông tin File Local
     * 
     * @return object|false
     */
    public function info()
    {
        if (!$this->filename) return false;
        $url = $this->connection->urlViewFile;
        list($year, $month, $day) = explode('_', $this->filename);
        $expFilename = explode('!~!', $this->filename);
        $path = '' . ($this->rootDir != '' ? $this->rootDir . '/' : '') . $year . '/' . $month . '/' . $day . '/' . $this->filename;
        $content = @fopen($url . $path, 'r', false, $this->contextFileGetContent);
        if ($content === false) return false;
        $fullPath = public_path('attach-file/' . $path);
        $cachedfilesize = filesize($fullPath);
        // thông tin file
        $info                     = new stdClass();
        $info->name               = $this->filename;
        $info->file_type          = substr($this->filename, strrpos($this->filename, '.') + 1);
        $info->cached_file_size   = $cachedfilesize;
        $info->creation_time      = date('Y-m-d H:i:s', filectime($fullPath));
        $info->last_modified_time = date('Y-m-d H:i:s', filemtime($fullPath));
        $info->mime_content_file  = mime_content_type($fullPath);
        $info->real_name          = end($expFilename);
        $info->filesize           = $this->humanFilesize($cachedfilesize);

        return $info;
    }
}
