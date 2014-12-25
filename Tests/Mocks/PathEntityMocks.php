<?php

namespace Parsingcorner\FilesBundle\Tests\Mocks;

use Parsingcorner\FilesBundle\Entity\Path;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PathEntityMocks extends WebTestCase
{

    public function getBasicEntity()
    {
		return $this->_setParams('/', 1);
    }

    public function getUserFtpEntity()
    {
    	return $this->_setParams('/home/ftpHomes/username', 3);
    }

    private function _setParams($path, $depth)
    {
        $pathEntity = new Path();
        $pathEntity->setPathAndDepth($path, $depth);

        return $pathEntity;
    }

}