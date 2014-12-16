<?php

namespace Kolekti\FilesBundle\Model;

use Kolekti\FilesBundle\Entity\FilePath;

/**
 * Model to processing the content of the files
 */
class FilesContentProcessing
{
    /**
     * Return the content of a file decoded.
     * @param  FilePath $filePath
     * @return string
     */
    public function getJsonDecodeContentFile(FilePath $filePath)
    {
        return json_decode($filePath->getContent());
    }

    /**
     * Return the content of a file encoded.
     * @param  FilePath $filePath
     * @return string
     */
    public function getJsonEncodeContentFile(FilePath $filePath)
    {
        return json_encode($filePath->getContent());
    }

}
        
