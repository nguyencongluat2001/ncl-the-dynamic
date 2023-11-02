<?php

namespace Modules\Core\Efy\Exceptions;

/**
 *
 * Lớp xử lý ngoại lệ của hệ thống
 */
class ResponseExeption extends \Exception
{

    /**
     *
     * @param code
     */
    public function __construct($message = "", $status = 400)
    {
        parent::__construct($message,$status);
    }

}