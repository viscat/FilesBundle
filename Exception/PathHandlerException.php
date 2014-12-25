<?php

namespace Parsingcorner\FilesBundle\Exception;

class PathHandlerException extends \Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}