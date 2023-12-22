<?php

namespace Modules\Base;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Str;

class LoggerHelpers {

    public $folderPath;

    private $channel = "infor";
    private $fileName = "log";


    function __construct() {
        $pathLink = storage_path('logs').'\\';
        $this->folderPath = $this->_createFolder($pathLink,date('Y'),date('m'),date('d'));
    }

    public function setChannel($channel){
        $this->channel = $channel;
        return $this;
    }

    public function setFileName($fileName){
        $this->fileName = $fileName;
        return $this;
    }

    public function log($message, $data=[]){

        $path = $this->folderPath."/". $this->fileName.".log";
        $stream = new StreamHandler($path, Logger::DEBUG);
        $stream->setFormatter($this->setFormat());
        $securityLogger = new Logger($this->channel);
        $securityLogger->pushHandler($stream);
        $securityLogger->info($message, ['data'=>$data]);
    }

    private function setFormat(){
        $dateFormat = "H:i:s d/m/Y";
        // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        $output = "[%datetime%] [%channel%] [%message%] [%context%] \n";
        return new LineFormatter($output, $dateFormat);
    }

    private function _createFolder($pathLink, $folderYear, $folderMonth, $sCurrentDay = "") {
     
        $sPath = str_replace("/", "\\", $pathLink);
        if (!file_exists($sPath . $folderYear)) {
            mkdir($sPath . $folderYear, 0777);
            $sPath = $sPath . $folderYear;
            if (!file_exists($sPath . chr(92) . $folderMonth)) {
                mkdir($sPath . chr(92) . $folderMonth, 0777);
            }
        } else {
            $sPath = $sPath . $folderYear;
            if (!file_exists($sPath . chr(92) . $folderMonth)) {
                mkdir($sPath . chr(92) . $folderMonth, 0777);
            }
        }
        //Tao ngay trong nam->thang
        if (!file_exists($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay)) {
            mkdir($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay, 0777);
        }
        //
        $strReturn = $pathLink . $folderYear . '/' . $folderMonth . '/' . $sCurrentDay . '/';
        return str_replace("/", "\\", $strReturn);
    }



}