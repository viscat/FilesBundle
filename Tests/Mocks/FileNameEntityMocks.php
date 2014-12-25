<?php

namespace Parsingcorner\FilesBundle\Tests\Mocks;

use Parsingcorner\FilesBundle\Entity\FileName;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileNameEntityMocks extends WebTestCase
{

    public function getBasicEntity()
    {
        $fileNameEntity = new FileName();
        $fileNameEntity->setBaseName('foo');
        $fileNameEntity->setExtension('bar');

        return $fileNameEntity;
    }

}