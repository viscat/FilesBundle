<?php

namespace Parsingcorner\FilesBundle\Tests\Model;

use Parsingcorner\FilesBundle\Model\FilesHandler;


class FilesHandlerTest extends IntegrationWebTestCase
{
    private $_filesEntitiesBuilder;
	private $_filePathEntity;


    protected function setUp()
    {
        $client = static::createClient();
        $this->_filesEntitiesBuilder = $client->getContainer()
                                              ->get('filesBundle.entitiesBuilder');
        $this->_filePathEntity = $this->_filesEntitiesBuilder
                                      ->buildFilePath('/foo/bar.php');
    }

    public function testCreateFileOk()
    {
        $filesHandler = $this->_setCreateFileOkFilesHandler();
        $this->assertTrue($filesHandler->createFile($this->_filePathEntity));
    }

    /**
     * @expectedException \Parsingcorner\FilesBundle\Exception\FilesHandlerException
     */
    public function testCreateFileKo()
    {
    	$filesHandler = $this->_setCreateFileKoFilesHandler();
        $filesHandler->createFile($this->_filePathEntity);
    }

    public function testReadFileOk()
    {
        $filesHandler = $this->_setReadFileOkFilesHandler();
        $this->assertTrue($filesHandler->readFile($this->_filePathEntity));
    }
    
    /**
     * @expectedException \Parsingcorner\FilesBundle\Exception\FilesHandlerException
     */
    public function testReadFileKo()
    {
        $filesHandler = $this->_setReadFileKoFilesHandler();
        $filesHandler->readFile($this->_filePathEntity);
    }

    public function testUpdateFileOk()
    {
        $filesHandler = $this->_setUpdateFileOkFilesHandler();
        $this->assertTrue($filesHandler->updateFile($this->_filePathEntity));
    }

    /**
     * @expectedException \Parsingcorner\FilesBundle\Exception\FilesHandlerException
     */
    public function testUpdateFileKo()
    {
        $filesHandler = $this->_setUpdateFileKoFilesHandler();
        $filesHandler->updateFile($this->_filePathEntity);
    }

    public function testDeleteFileOk()
    {
        $filesHandler = $this->_setDeleteFileFileOkFilesHandler();
        $this->assertTrue($filesHandler->deleteFile($this->_filePathEntity));
    }

    /**
     * @expectedException \Parsingcorner\FilesBundle\Exception\FilesHandlerException
     */
    public function testDeleteFileKo()
    {
        $filesHandler = $this->_setDeleteFileFileKoFilesHandler();
        $filesHandler->deleteFile($this->_filePathEntity);
    }

    private function _setCreateFileOkFilesHandler()
    {
        $filesMaster = $this->getMock('Parsingcorner\FilesBundle\Model\FileMaster');
        $this->_fileExists($filesMaster, false);
        $this->_setContent($filesMaster, true);

        return new FilesHandler($filesMaster, $this->_filesEntitiesBuilder);
    }   

    private function _setCreateFileKoFilesHandler()
    {
        $filesMaster = $this->getMock('Parsingcorner\FilesBundle\Model\FileMaster');
        $this->_fileExists($filesMaster, true);
        $this->_setContent($filesMaster, false);

        return new FilesHandler($filesMaster, $this->_filesEntitiesBuilder);
    }   

    private function _setReadFileOkFilesHandler()
    {
        $filesMaster = $this->getMock('Parsingcorner\FilesBundle\Model\FileMaster');
        $this->_fileExists($filesMaster, true);
        $this->_readFile($filesMaster, 'foo');

        $statsArray = array('size' => 100,
                            'mtime' => 1414439403,
                            'atime' => 1414439403,
                            'uid' => 1,
                            'gid' => 1
                            );
        $this->_getFileStats($filesMaster, $statsArray);

        return new FilesHandler($filesMaster, $this->_filesEntitiesBuilder);
    }   

    private function _setReadFileKoFilesHandler()
    {
        $filesMaster = $this->getMock('Parsingcorner\FilesBundle\Model\FileMaster');
        $this->_fileExists($filesMaster, false);
        $this->_readFile($filesMaster, null);
        $this->_getFileStats($filesMaster, false);

        return new FilesHandler($filesMaster, $this->_filesEntitiesBuilder);
    }   

    private function _setUpdateFileOkFilesHandler()
    {
        $filesMaster = $this->getMock('Parsingcorner\FilesBundle\Model\FileMaster');
        $this->_fileExists($filesMaster, true);
        $this->_setContent($filesMaster, true);

        return new FilesHandler($filesMaster, $this->_filesEntitiesBuilder);
    }   

    private function _setUpdateFileKoFilesHandler()
    {
        $filesMaster = $this->getMock('Parsingcorner\FilesBundle\Model\FileMaster');
        $this->_fileExists($filesMaster, false);
        $this->_setContent($filesMaster, false);

        return new FilesHandler($filesMaster, $this->_filesEntitiesBuilder);
    }   

    private function _setDeleteFileFileOkFilesHandler()
    {
        $filesMaster = $this->getMock('Parsingcorner\FilesBundle\Model\FileMaster');
        $this->_removeFile($filesMaster, true);

        return new FilesHandler($filesMaster, $this->_filesEntitiesBuilder);
    }   

    private function _setDeleteFileFileKoFilesHandler()
    {
        $filesMaster = $this->getMock('Parsingcorner\FilesBundle\Model\FileMaster');
        $this->_removeFile($filesMaster, false);

        return new FilesHandler($filesMaster, $this->_filesEntitiesBuilder);
    }   

    private function _fileExists(&$filesMaster, $option)
    {
        $filesMaster->expects($this->any())
                    ->method('fileExists')
                    ->will($this->returnValue($option));
    }

    private function _setContent(&$filesMaster, $option)
    {
        $filesMaster->expects($this->any())
                    ->method('setContent')
                    ->will($this->returnValue($option));
    }

    private function _readFile(&$filesMaster, $option)
    {
        $filesMaster->expects($this->any())
                    ->method('readFile')
                    ->will($this->returnValue($option));
    }

    private function _getFileStats(&$filesMaster, $option)
    {
        $filesMaster->expects($this->any())
                    ->method('getFileStats')
                    ->will($this->returnValue($option));
    }

    private function _removeFile(&$filesMaster, $option)
    {
        $filesMaster->expects($this->any())
                    ->method('removeFile')
                    ->will($this->returnValue($option));
    }

}