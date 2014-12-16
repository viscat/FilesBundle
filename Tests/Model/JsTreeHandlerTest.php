<?php

namespace Kolekti\FilesBundle\Tests\Model;

use Kolekti\FilesBundle\Model\JsTreeHandler;

use Kolekti\FilesBundle\Tests\Mocks\FilesHandlerMocks;
use Kolekti\FilesBundle\Tests\Mocks\PathHandlerMocks;
use Kolekti\UserBundle\Tests\Mocks\TblUsersEntityMocks;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JsTreeHandlerTest extends WebTestCase
{
    private $_client;
    private $_forbiddenDirectories;
    private $_jsTreeBasePathChar;
    private $_filesHandlerMocks;
    private $_pathHandlerMocks;
    private $_usersEntityMocks;

    protected function setUp()
    {
        $this->_client = static::createClient();
        $this->_forbiddenDirectories = $this->_client->getContainer()->getParameter('kolekti_filesBundle.jsTreeDirectoriesToSkip');
        $this->_jsTreeBasePathChar = $this->_client->getContainer()->getParameter('kolekti_filesBundle.jsTreeBasePathChar');

        $this->_filesHandlerMocks = new FilesHandlerMocks();
        $this->_pathHandlerMocks = new PathHandlerMocks();
        $this->_usersEntityMocks = new TblUsersEntityMocks();

        $this->_jsTreeHandlerBasic = new JsTreeHandler($this->_filesHandlerMocks->getBasicMock(),
                                                       $this->_pathHandlerMocks->getBasicMock(),
                                                       $this->_client->getContainer()->get('kolekti_security.crypt'),
                                                       $this->_forbiddenDirectories,
                                                       $this->_jsTreeBasePathChar);
    }

    public function testListDirectoryBasic1()
    {
        $r = $this->_jsTreeHandlerBasic->listDirectory($this->_usersEntityMocks
                                                            ->getBasicEntity(), '#');
        $this->assertEquals($this->_getListDirectoryBasicExpected(), $r);
    }

    public function testListDirectoryBasic2()
    {
        $r = $this->_jsTreeHandlerBasic->listDirectory($this->_usersEntityMocks
                                                            ->getBasicEntity(),
                                                       'L2hvbWUvZnRwSG9tZXMvdXNlcg==');
        $this->assertEquals($this->_getListDirectoryBasicExpected(), $r);
    }

    
    public function testListDirectoryAllDirectories()
    {
        $jsTreeHandler = new JsTreeHandler($this->_filesHandlerMocks->getPathReturnMock(),
                                           $this->_pathHandlerMocks->getBasicMock(),
                                           $this->_client->getContainer()->get('kolekti_security.crypt'),
                                           $this->_forbiddenDirectories,
                                           $this->_jsTreeBasePathChar);

        $r = $jsTreeHandler->listDirectory($this->_usersEntityMocks->getBasicEntity(), '#');
        $this->assertEquals($this->_getListDirectoryAllDirectoriesExpected(), $r);
    }

    public function testGetNodeContentBasic()
    {
        $r = $this->_jsTreeHandlerBasic
                  ->getNodeContent($this->_usersEntityMocks->getBasicEntity(), '#');

        $this->assertEquals('foo', $r);
    }

    /**
     * @expectedException Kolekti\FilesBundle\Exception\DisallowFilesystemAccessException
     */
    public function testFailTryingToAccessToDisallowedPath1()
    {
        $this->_jsTreeHandlerBasic
             ->listDirectory($this->_usersEntityMocks->getBasicEntity(), 'Lw==');
    }

    /**
     * @expectedException Kolekti\FilesBundle\Exception\DisallowFilesystemAccessException
     */
    public function testFailTryingToAccessToDisallowedPath2()
    {
        $this->_jsTreeHandlerBasic
             ->listDirectory($this->_usersEntityMocks->getBasicEntity(),
                             '/home/ftpHomes/otherUser');
    }

    /**
     * @expectedException Kolekti\FilesBundle\Exception\DisallowFilesystemAccessException
     */
    public function testFailTryingToAccessToDisallowedPath3()
    {
        $this->_jsTreeHandlerBasic
             ->listDirectory($this->_usersEntityMocks->getBasicEntity(),
                             'L2hvbWUvZnRwSG9tZXMvb3RoZXJVc2Vy');
    }

    /**
     * @expectedException Kolekti\FilesBundle\Exception\DisallowFilesystemAccessException
     */
    public function testFailTryingToAccessToDisallowedPath4()
    {
        $this->_jsTreeHandlerBasic
             ->getNodeContent($this->_usersEntityMocks->getBasicEntity(), 'Lw==');
    }

    /**
     * @expectedException Kolekti\FilesBundle\Exception\DisallowFilesystemAccessException
     */
    public function testFailTryingToAccessToDisallowedPath5()
    {
        $this->_jsTreeHandlerBasic
             ->getNodeContent($this->_usersEntityMocks->getBasicEntity(),
                              '/home/ftpHomes/otherUser');
    }

    /**
     * @expectedException Kolekti\FilesBundle\Exception\DisallowFilesystemAccessException
     */
    public function testFailTryingToAccessToDisallowedPath6()
    {
        $this->_jsTreeHandlerBasic
             ->getNodeContent($this->_usersEntityMocks->getBasicEntity(),
                              'L2hvbWUvZnRwSG9tZXMvb3RoZXJVc2Vy');
    }

    private function _getListDirectoryBasicExpected()
    {
        return array(
                    array('id' => 'L2Zvby5iYXI=',
                          'text' => 'foo',
                          'content' => '',
                          'children' => '',
                          'type' => 'file',
                          'icon' => '',
                          'state' => '',
                          ),
                    array('id' => 'L2Zvby5iYXI=',
                          'text' => 'bar',
                          'content' => '',
                          'children' => '',
                          'type' => 'file',
                          'icon' => '',
                          'state' => '',
                          ),
               );
    }

    private function _getListDirectoryAllDirectoriesExpected()
    {
        return array(
                    array('id' => 'Lw==',
                          'text' => 'foo',
                          'content' => '',
                          'children' => 1,
                          'type' => 'folder',
                          'icon' => '',
                          'state' => '',
                          ),
                    array('id' => 'Lw==',
                          'text' => 'bar',
                          'content' => '',
                          'children' => 1,
                          'type' => 'folder',
                          'icon' => '',
                          'state' => '',
                          ),
               );
    }

}