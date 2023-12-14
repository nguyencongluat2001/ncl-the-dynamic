<?php

namespace Modules\Core\Ncl\File;

use Illuminate\Support\Str;
use Modules\Core\Ncl\File\FileLocal\ViewFileLocal;
use Modules\Core\Ncl\File\FileServer\ViewFileServer;

/**
 * Xem/Lấy thông tin File Local hoặc File Server
 * 
 * @author luatnc
 */
class ViewFile
{

    /**
     * Thông tin truyền vào
     */
    private $params;

    /**
     * Các cấu hình kết nối
     */
    private $config;

    public function __construct($params)
    {
        // Nếu tên file là stream_id (uuid format) và chưa có streamId thì gán nó thành streamId
        if (Str::isUuid($params['filename']) && empty($params['streamId'])) {
            $params['streamId'] = $params['filename'];
            unset($params['filename']);
        }
        $this->params = $params;
    }

    /**
     * Kiểm tra các trường bắt buộc để view file và lấy ra config
     * 
     * @return boolean
     */
    private function check()
    {
        if (empty($this->params['filename']) && empty($this->params['streamId'])) return false;
        $this->config = $this->configViewFile();
        return true;
    }

    /**
     * Lấy các config
     * 
     * @param int $type 1: lấy config view file server; 0 lấy config view file local
     * @return array
     */
    private function configViewFile()
    {
        $configView = config('file.ViewFile');
        $config     = array();
        if ($this->params['year'] != '') {
            $year = $this->params['year'];
        } else if (!empty($this->params['filename'])) {
            list($year) = explode('_', $this->params['filename']);
        } else {
            $year = date('Y');
        }
        array_map(function ($v) use (&$config, $year) {
            if (array_search($year, $v['year']) !== false) array_push($config, $v);
        }, $configView);

        return $config;
    }

    /**
     * Xem file
     * 
     * @return Response|false
     */
    public function view()
    {
        if ($this->check() === false) return false;
        foreach ($this->config as $conf) {
            $view = false;
            if ($conf['type'] == 'FileServer') {
                $view = (new ViewFileServer($conf, $this->params))->view();
            } else {
                $view = (new ViewFileLocal($conf, $this->params))->view();
            }
            if ($view !== false) return $view;
        }

        return response('<div style="text-align: center;">
            <h1 style="padding-top: 3rem">Tệp không tồn tại</h1>
            File not found
        </div>', 404);
    }

    /**
     * Lấy url view file
     * 
     * @return string|false
     */
    public function url()
    {
        if ($this->check() === false) return false;
        foreach ($this->config as $conf) {
            $url = false;
            if ($conf['type'] == 'FileServer') {
                $url = (new ViewFileServer($conf, $this->params))->url();
            } else {
                $url = (new ViewFileLocal($conf, $this->params))->url();
            }
            if ($url !== false) return $url;
        }

        return false;
    }

    /**
     * Thông tin File Local
     * 
     * @return object|false
     */
    public function info()
    {
        if ($this->check() === false) return false;
        foreach ($this->config as $conf) {
            $info = false;
            if ($conf['type'] == 'FileServer') {
                $info = (new ViewFileServer($conf, $this->params))->info();
            } else {
                $info = (new ViewFileLocal($conf, $this->params))->info();
            }
            if ($info !== false) return $info;
        }

        return false;
    }
}
