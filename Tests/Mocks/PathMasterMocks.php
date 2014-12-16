<?php

namespace Kolekti\FilesBundle\Tests\Mocks;

use Kolekti\FilesBundle\Entity\Path;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PathMasterMocks extends WebTestCase
{
    private $_pathMasterBaseMock;

    public function __construct()
    {
        $this->_pathMasterBaseMock = $this->getMockBuilder('Kolekti\FilesBundle\Model\PathMaster')
                                          ->disableOriginalConstructor()
                                          ->getMock();
    }

    public function getBasicMock()
    {        
        $this->_setBasicData();
        $this->_setBasicScanPathData();

        return $this->_pathMasterBaseMock;
    }

    public function getScanFailedMock()
    {        
        $this->_setBasicData();
        $this->_setFailedScanPath();

        return $this->_pathMasterBaseMock;
    }

    public function getDirectoryCreationFailedMock()
    {
        $this->_pathMasterBaseMock
             ->expects($this->any())
             ->method('createDirectoy')
             ->will($this->returnValue(false));
             
        $this->_pathMasterBaseMock
             ->expects($this->any())
             ->method('createDirectoryRecursively')
             ->will($this->returnValue(false));

        $this->_pathMasterBaseMock
             ->expects($this->any())
             ->method('pathExists')
             ->will($this->returnValue(true));

        $this->_setBasicScanPathData();

        return $this->_pathMasterBaseMock;
    }

    private function _setBasicData()
    {
        $this->_pathMasterBaseMock
             ->expects($this->any())
             ->method('createDirectoy')
             ->will($this->returnValue(true));
             
        $this->_pathMasterBaseMock
             ->expects($this->any())
             ->method('createDirectoryRecursively')
             ->will($this->returnValue(true));

        $this->_pathMasterBaseMock
             ->expects($this->any())
             ->method('pathExists')
             ->will($this->returnValue(false));
    }

    private function _setBasicScanPathData()
    {
        $this->_pathMasterBaseMock
             ->expects($this->any())
             ->method('scanPath')
             ->will($this->returnValue(array('.','..','foo','bar')));
    }

    private function _setFailedScanPath()
    {
        $this->_pathMasterBaseMock
             ->expects($this->any())
             ->method('scanPath')
             ->will($this->returnValue(false));
    }

}