<?php

namespace Kolekti\FilesBundle\Tests\Model;

use Kolekti\FilesBundle\Model\PathHandler;
use Kolekti\FilesBundle\Tests\Mocks\PathEntityMocks;
use Kolekti\FilesBundle\Tests\Mocks\PathMasterMocks;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PathHandlerTest extends WebTestCase
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
     * @expectedException \Kolekti\FilesBundle\Exception\PathHandlerException
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
     * @expectedException \Kolekti\FilesBundle\Exception\PathHandlerException
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