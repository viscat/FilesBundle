<?php

namespace Parsingcorner\FilesBundle\Tests\Mocks;

use Parsingcorner\FilesBundle\Entity\FilePath;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FilePathEntityMocks extends WebTestCase
{

    private $_fileNameEntityMocks;
    private $_pathEntityMocks;

    public function __construct()
    {
        $this->_fileNameEntityMocks = new FileNameEntityMocks();
        $this->_pathEntityMocks = new PathEntityMocks();
    }

    public function getBasicEntity()
    {
		$filePathEntity = new FilePath();
        $filePathEntity->setFullFilePath($this->_pathEntityMocks
                                              ->getBasicEntity(),
                                         $this->_fileNameEntityMocks
                                              ->getBasicEntity());
        $filePathEntity->setPath($this->_pathEntityMocks
                                      ->getBasicEntity());
        $filePathEntity->setFileName($this->_fileNameEntityMocks
                                          ->getBasicEntity());
        $filePathEntity->setSize(3);
        $filePathEntity->setLastModification(new \DateTime());
        $filePathEntity->setLastAccess(new \DateTime());
        $filePathEntity->setOwnerId(1);
        $filePathEntity->setGroupId(1);
        $filePathEntity->setContent('foo');

        return $filePathEntity;
    }

}