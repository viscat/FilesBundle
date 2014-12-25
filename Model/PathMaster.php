<?php

namespace Parsingcorner\FilesBundle\Model;

use Parsingcorner\FilesBundle\Entity\Path;

/**
 * Model to perform the filesystem directories actions
 */
class PathMaster
{
	/**
	 * Default directory creation permissions
	 * @var int
	 */
	private $_defaultDirPermissions;

	/**
	 * @param int $defaultDirPermissions Unix filesystem permission value
	 */
	public function __construct($defaultDirPermissions)
	{
		$this->_defaultDirPermissions = $defaultDirPermissions;
	}

	/**
	 * Creates a directory non recursively
	 * 
	 * @param  Path   $path
	 * @return bool
	 */
	public function createDirectoy(Path $path)
	{
		return $this->_createDirectory($path, false);
	}

	/**
	 * Creates a directory recursively
	 * 
	 * @param  Path   $path
	 * @return bool
	 */
	public function createDirectoryRecursively(Path $path)
	{
		return $this->_createDirectory($path, true);
	}

	/**
	 * Check if the selected path exists
	 * 
	 * @param  Path   $path
	 * @return bool
	 */
	public function pathExists(Path $path)
	{
		return is_dir($path->getPath());
	}

	/**
	 * Scan a directory to get the files and directories list
	 * 
	 * @param  Path   $path
	 * @return array
	 */
	public function scanPath(Path $path)
	{
		return @scandir($path->getPath());
	}
	
	/**
	 * Do the directory creation action
	 * 
	 * @param  Path   $path
	 * @param  bool $recursive
	 * @return bool
	 */
	private function _createDirectory(Path $path, $recursive)
	{
  		return mkdir($path->getPath(),
  			         $this->_defaultDirPermissions,
  			         $recursive);
	}

}
