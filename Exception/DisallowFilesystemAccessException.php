<?php

namespace Kolekti\FilesBundle\Exception;

class DisallowFilesystemAccessException extends \Exception
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct('The selected user does not have the necessary'
        	                . ' rights to see the requested node.',
        	                $code,
        	                $previous);
    }
} 