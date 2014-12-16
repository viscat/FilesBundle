<?php

namespace Kolekti\FilesBundle\Tests\Mocks;

use Kolekti\FilesBundle\Tests\Mocks\FileNameEntityMocks;
use Kolekti\FilesBundle\Tests\Mocks\PathEntityMocks;
use Kolekti\FilesBundle\Tests\Mocks\FilePathEntityMocks;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FilesHandlerMocks extends WebTestCase
{
    private $_fileNameEntityMocks;
    private $_pathEntityMocks;
    private $_filePathEntityMocks;
    private $_filesHandlerBaseMock;

    public function __construct()
    {
        $this->_fileNameEntityMocks = new FileNameEntityMocks();
        $this->_pathEntityMocks = new PathEntityMocks();
        $this->_filePathEntityMocks = new FilePathEntityMocks();
    }

    private function _initBaseMock()
    {
        $this->_filesHandlerBaseMock = $this->getMockBuilder('Kolekti\FilesBundle\Model\FilesHandler')
                                            ->disableOriginalConstructor()
                                            ->getMock();
    }

    private function _setCommonParams()
    {
        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('buildFileNameEntity')
             ->will($this->returnValue($this->_fileNameEntityMocks
                                            ->getBasicEntity()
                                            ));

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('buildPathEntity')
             ->will($this->returnValue($this->_pathEntityMocks
                                            ->getBasicEntity()
                                            ));

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('buildFilePath')
             ->will($this->returnValue($this->_filePathEntityMocks
                                            ->getBasicEntity()
                                            ));

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('buildFilePathAndRead')
             ->will($this->returnValue($this->_filePathEntityMocks
                                            ->getBasicEntity()
                                      ));

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('createFile')
             ->will($this->returnValue(true));

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('readFile')
             ->will($this->returnValue(true));

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('updateFile')
             ->will($this->returnValue(true));

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('deleteFile')
             ->will($this->returnValue(true));
    }

    public function getBasicMock()
    {
        $this->_initBaseMock();
        $this->_setCommonParams();

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('buildEntityFromExistentPath')
             ->will($this->returnValue($this->_filePathEntityMocks
                                            ->getBasicEntity()
                                            ));

        return $this->_filesHandlerBaseMock;
    }

    public function getPathReturnMock()
    {
        $this->_initBaseMock();
        $this->_setCommonParams();

        $this->_filesHandlerBaseMock
             ->expects($this->any())
             ->method('buildEntityFromExistentPath')
             ->will($this->returnValue($this->_pathEntityMocks
                                            ->getBasicEntity()
                                            ));

        return $this->_filesHandlerBaseMock;
    }

}