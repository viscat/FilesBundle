<?php

namespace Parsingcorner\FilesBundle\Tests\Model;

use Parsingcorner\FilesBundle\Model\PathHandler;
use Parsingcorner\FilesBundle\Tests\Mocks\PathEntityMocks;
use Parsingcorner\FilesBundle\Tests\Mocks\PathMasterMocks;

class PathHandlerTest extends IntegrationWebTestCase
{

    private $_pathMasterMocks;
    private $_pathHandlerService;


    protected function setUp()
    {
        $this->_pathEntityMocks = new PathEntityMocks();
        $this->_pathEntityBasicMock = $this->_pathEntityMocks->getBasicEntity();
        $this->_pathMasterMocks = new PathMasterMocks();
    }

    public function testCreateDirectoryRecursivelyOk()
    {
        $pathHandler = $this->_getPathHandler($this->_pathMasterMocks
                                                   ->getBasicMock());
        $created = $pathHandler->createDirectoryRecursively($this->_pathEntityBasicMock);
        $this->assertTrue($created);
    }

    /**
     * @expectedException \Parsingcorner\FilesBundle\Exception\PathHandlerException
     */
    public function testCreateDirectoryRecursivelyKo()
    {
        $pathHandler = $this->_getPathHandler($this->_pathMasterMocks
                                                   ->getDirectoryCreationFailedMock());
        $pathHandler->createDirectoryRecursively($this->_pathEntityBasicMock);
    }

    public function testScanPathOk()
    {
        $pathHandler = $this->_getPathHandler($this->_pathMasterMocks
                                                   ->getBasicMock());
        $dirList = $pathHandler->scanPath($this->_pathEntityBasicMock);
        $this->assertEquals(array('.','..','foo','bar'), $dirList);
    }

    /**
     * @expectedException \Parsingcorner\FilesBundle\Exception\PathHandlerException
     */
    public function testScanPathKo()
    {
        $pathHandler = $this->_getPathHandler($this->_pathMasterMocks
                                                   ->getScanFailedMock());
        $pathHandler->scanPath($this->_pathEntityBasicMock);
    }

    private function _getPathHandler($pathMasterMock)
    {
        return new PathHandler($pathMasterMock);
    }

}