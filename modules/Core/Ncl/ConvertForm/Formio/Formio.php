<?php

namespace Modules\Core\Ncl\ConvertForm\Formio;

/**
 * Formio class
 * 
 * @author luatnc
 */
class Formio implements FormioInterface
{
    private $strJson;
    private $map;

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
