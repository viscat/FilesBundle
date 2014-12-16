<?php

namespace Kolekti\FilesBundle\Tests\Mocks;

use Kolekti\FilesBundle\Entity\Path;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PathHandlerMocks extends WebTestCase
{
    private $_pathHandlerBaseMock;

    public function __construct()
    {
        $this->_pathHandlerBaseMock = $this->getMockBuilder('Kolekti\FilesBundle\Model\PathHandler')
                                           ->disableOriginalConstructor()
                                           ->getMock();
    }

    public function getBasicMock()
    {        
        $this->_pathHandlerBaseMock
             ->expects($this->any())
             ->method('pathExists')
             ->will($this->returnValue(true));
             
        $this->_pathHandlerBaseMock
             ->expects($this->any())
             ->method('createDirectoryRecursively')
             ->will($this->returnValue(true));

        $this->_pathHandlerBaseMock
             ->expects($this->any())
             ->method('scanPath')
             ->will($this->returnValue(array('.','..','foo','bar')));

        return $this->_pathHandlerBaseMock;
    }

}