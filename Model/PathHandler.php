<?php

namespace Parsingcorner\FilesBundle\Model;

use Parsingcorner\FilesBundle\Exception\PathHandlerException as Exception;

use Parsingcorner\FilesBundle\Exception\PathHandlerException;
use Parsingcorner\FilesBundle\Model\PathMaster;
use Parsingcorner\FilesBundle\Entity\Path;

/**
 * Model to handle the filesystem directory interaction
 */
class PathHandler
{
	/**
	 * @var PathMaster
	 */
	private $_pathMaster;

	/**
	 * @param PathMaster $pathMaster
	 */
	public function __construct(PathMaster $pathMaster)
	{
		$this->_pathMaster = $pathMaster;
	}

	/**
	 * Creates a directory recursively
	 * 
	 * @param  Path   $path
	 * @throws PathHandlerException if cannot create the directory
	 * @return bool Create confirmation
	 */
	public function createDirectoryRecursively(Path $path)
	{
		if (!$dirCreated = $this->_pathMaster->createDirectoryRecursively($path))
			throw new Exception('The directory "'
			                    . $path->getPath()
			                    . '" cannot being created.');

		return $dirCreated;
	}

	/**
	 * Check if a directory exists in the filesystem
	 * 
	 * @param  Path   $path
	 * @return bool
	 */
	public function pathExists(Path $path)
	{
		return $this->_pathMaster->pathExists($path);
	}

	/**
	 * Scan a directory to get the files and directories list
	 * 
	 * @param  Path   $path
	 * @throws \FilesBundle\Exception\PathHandlerException if cannot scan the path
	 * @return array
	 */
	public function scanPath(Path $path)
	{
		if(!$lst = $this->_pathMaster->scanPath($path))
			throw new Exception('Could not scan path: ' . $path->getPath());

		return $lst;
	}
}
