<?php

namespace Parsingcorner\FilesBundle\Tests\Model;


class FilesEntitiesBuilderTest extends IntegrationWebTestCase
{

    private $_filesEntitiesBuilder;
    private $_fileSamples;
    private $_pathSamples;
    private $_fullFilePathSamples;

    protected function setUp()
    {
        $client = static::createClient();
        $this->_filesEntitiesBuilder = $client->getContainer()
                                              ->get('filesBundle.entitiesBuilder');
        $this->_setFileNameSamples();
        $this->_setPathSamples();
        $this->_setFullFilePathsSamples();
    }

    public function testBuildFileNameEntity()
    {
        foreach ($this->_fileSamples as $fullFileName => $assertsData) {
            $fileNameEntity = $this->_filesEntitiesBuilder
                                   ->buildFileNameEntity($fullFileName);

            $this->assertEquals($assertsData[0], $fileNameEntity->getBasename());
            $this->assertEquals($assertsData[1], $fileNameEntity->getExtension());

        }

    }

    public function testBuildPathEntity()
    {
        foreach ($this->_pathSamples as $path => $assertsData) {
            $fileNameEntity = $this->_filesEntitiesBuilder
                                   ->buildPathEntity($path);

            $this->assertEquals($assertsData[0], $fileNameEntity->getPath());
            $this->assertEquals($assertsData[1], $fileNameEntity->getDepth());

        }

    }

    public function testBuildFilePathEntity()
    {
        foreach ($this->_fullFilePathSamples as $filePath => $assertsData) {
            $filePathEntity = $this->_filesEntitiesBuilder
                                   ->buildFilePath($filePath);

            $this->assertEquals($assertsData[0],
                                $filePathEntity->getPath()->getPath());
            $this->assertEquals($assertsData[1],
                                $filePathEntity->getFileName()->getBasename());
            $this->assertEquals($assertsData[2],
                                $filePathEntity->getFileName()->getExtension());
            $this->assertEquals($filePath,
                                $filePathEntity->getFullFilePath());

        }

    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testBuildFileNameEntityException1()
    {
        $this->_filesEntitiesBuilder->buildFileNameEntity('/faaaail');
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testBuildFileNameEntityException2()
    {
        $this->_filesEntitiesBuilder->buildFileNameEntity('faaaail/');
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testBuildFileNameEntityException3()
    {
        $this->_filesEntitiesBuilder->buildFileNameEntity('/faaaail/');
    }

    /**
     * @expectedException \Parsingcorner\FilesBundle\Exception\FilesEntitesBuilderException
     */
    public function testBuildFilePathEntityException1()
    {
        $this->_filesEntitiesBuilder->buildFilePath('/');
    }

    /**
     * @expectedException \Parsingcorner\FilesBundle\Exception\FilesEntitesBuilderException
     */
    public function testBuildFilePathEntityException2()
    {
        $this->_filesEntitiesBuilder->buildFilePath('/foo/bar/');
    }

    private function _setFileNameSamples()
    {
        $this->_fileSamples = array();
        $this->_fileSamples['foo.bar'] = array('foo', 'bar');
        $this->_fileSamples['foo.min.bar'] = array('foo', 'min.bar');
        $this->_fileSamples['noExtension'] = array('noExtension', '');
    }

    private function _setPathSamples()
    {
        $this->_pathSamples = array();
        $this->_pathSamples['/'] = array('/', 0);
        $this->_pathSamples['/foo'] = array('/foo/', 1);
        $this->_pathSamples['/foo/'] = array('/foo/', 1);
        $this->_pathSamples['/foo/bar'] = array('/foo/bar/', 2);
        $this->_pathSamples['/foo//bar'] = array('/foo/bar/', 2);
        $this->_pathSamples['/foo/bar/foo/bar/'] = array('/foo/bar/foo/bar/', 4);
        $this->_pathSamples['/foo/bar/foo/bar/foo'] = array('/foo/bar/foo/bar/foo/', 5);
    }

    private function _setFullFilePathsSamples()
    {
        $this->_fullFilePathSamples = array();
        $this->_fullFilePathSamples['/foo/bar'] = array('/foo/', 'bar', '');
        $this->_fullFilePathSamples['/foo/bar.com'] = array('/foo/', 'bar', 'com');
        $this->_fullFilePathSamples['/foo/bar.min.com'] = array('/foo/', 'bar', 'min.com');
        $this->_fullFilePathSamples['/foo/bar/foo/bar.min.com'] = array('/foo/bar/foo/', 'bar', 'min.com');
    }

}