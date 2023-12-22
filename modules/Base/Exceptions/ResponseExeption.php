<?php

namespace Modules\Core\Ncl\Exceptions;

class ResponseExeption extends \Exception
{
    public function __construct($message = "", $status = 400)
    {
        parent::__construct($message,$status);
    }

}