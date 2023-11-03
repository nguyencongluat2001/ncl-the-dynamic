<?php

namespace Modules\Core\Ncl\ConvertForm\Formly;

/**
 * Formly class
 * 
 * @author khuongtq
 */
class Formly implements FormlyInterface
{
    private $strJson;

    public function __construct(string $strJson)
    {
        $this->strJson = $strJson;
    }

    /**
     * Lấy chuỗi JSON
     * 
     * @return string
     */
    public function get(): string
    {
        return $this->strJson;
    }
}
