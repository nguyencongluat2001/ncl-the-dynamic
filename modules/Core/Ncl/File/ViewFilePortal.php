<?php

namespace Modules\Core\Ncl\File;

use finfo;
use Illuminate\Support\Facades\DB;

/**
 * View file từ Dịch vụ công
 * 
 * @author luatnc
 */
class ViewFilePortal
{
    private $filename;
    private $contextFileGetContent;

    public function __construct($filename)
    {
        $this->filename              = $filename;
        $this->contextFileGetContent = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ]);
    }

    /**
     * Url file từ Dịch vụ công
     * 
     * @param string
     */
    public function url()
    {
        if (!$this->filename) return false;
        // get url from t_files portal
        $url = $this->getUrlFromTable();
        if ($url !== false) return $url;

        return $this->getUrlFromFilename();
    }

    /**
     * Lấy Response file
     * 
     * @return Response|false
     */
    public function content()
    {
        if (!$this->filename) return false;
        $content = $this->contentFromTable();
        if ($content !== false) return $content;

        return $this->contentFromFilename();
    }

    /**
     * Lấy Response file
     * 
     * @return Response|false
     */
    public function view()
    {
        if (!$this->filename) return false;
        $view = $this->viewFromTable();
        if ($view !== false) return $view;

        return $this->viewFromFilename();
    }

    /**
     * Lấy file từ bảng t_files ở portal
     * 
     * @return string|false
     */
    private function getUrlFromTable()
    {
        $file = DB::connection('sqlsrvPortal')->table('files')
            ->where('filename', $this->filename)
            ->first();
        if ($file) return $file->url;

        return false;
    }

    /**
     * Lấy url từ tên file
     * 
     * @return string
     */
    private function getUrlFromFilename()
    {
        $url                      = '';
        list($year, $month, $day) = explode('_', $this->filename);
        $folder                   = $year . chr(47) . $month . chr(47) . $day . chr(47);
        $url                      = config('file.UrlPortal', 'http://dichvucong.thainguyen.gov.vn/portal/') . 'public/attach-file/' . $folder . $this->filename;

        return $url;
    }
    /**
     * Lấy content file từ url trong bảng t_files
     * 
     * @return string|false
     */
    private function contentFromTable()
    {
        $url = $this->getUrlFromTable();
        if ($url === false) return false;
        $content = @file_get_contents($url, false, $this->contextFileGetContent);

        return $content;
        // $expFilename = explode('!~!', $this->filename);
        // $finfo       = new finfo(FILEINFO_MIME);
        // $mime        = $finfo->buffer($content);

        // return response($content, 200, [
        //     'Content-Type'        => $mime,
        //     'Content-Disposition' => 'inline; filename="' . end($expFilename) . '"',
        // ]);
    }

    /**
     * Lấy content file từ url tên file
     * 
     * @return string|false
     */
    private function contentFromFilename()
    {
        $url = $this->getUrlFromFilename();
        if ($url === false) return false;
        $content = @file_get_contents($url, false, $this->contextFileGetContent);

        return $content;
        // $expFilename = explode('!~!', $this->filename);
        // $finfo       = new finfo(FILEINFO_MIME);
        // $mime        = $finfo->buffer($content);

        // return response($content, 200, [
        //     'Content-Type'        => $mime,
        //     'Content-Disposition' => 'inline; filename="' . end($expFilename) . '"',
        // ]);
    }

    /**
     * Lấy response view file từ url trong bảng t_files
     * 
     * @return Response|false
     */
    private function viewFromTable()
    {
        $content = $this->contentFromTable();
        if ($content === false) return false;
        $expFilename = explode('!~!', $this->filename);
        $finfo       = new finfo(FILEINFO_MIME);
        $mime        = $finfo->buffer($content);

        return response($content, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . end($expFilename) . '"',
        ]);
    }

    /**
     * Lấy response view file từ tên file
     * 
     * @return Response|false
     */
    private function viewFromFilename()
    {
        $content = $this->contentFromFilename();
        if ($content === false) return false;
        $expFilename = explode('!~!', $this->filename);
        $finfo       = new finfo(FILEINFO_MIME);
        $mime        = $finfo->buffer($content);

        return response($content, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . end($expFilename) . '"',
        ]);
    }
}
