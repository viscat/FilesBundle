<?php

namespace Kolekti\FilesBundle\Model;

use Kolekti\FilesBundle\Entity\FilePath;

/**
 * Model to interact with filesystem files
 */
class FileMaster
{
	/**
	 * Check if the selected file exists
	 * 
	 * @param  FilePath $filePath
	 * @return bool
	 */
	public function fileExists(FilePath $filePath)
	{
		return is_file($filePath->getFullFilePath());
	}

	/**
	 * Read and returns the content of a file
	 * 
	 * @param  FilePath $filePath
	 * @return string
	 */
	public function readFile(FilePath $filePath)
	{
		return file_get_contents($filePath->getFullFilePath());
	}

	/**
	 * Returns files diverse stats
	 * 
	 * @param  FilePath $filePath
	 * @return array
	 */
	public function getFileStats(FilePath $filePath)
	{
		return stat($filePath->getFullFilePath());
	}

	/**
	 * Create a file and set the content or overwrite it if already exists
	 * 
	 * @param FilePath $filePath
	 * @return mixed http://php.net/manual/es/function.file-put-contents.php
	 */
	public function setContent(FilePath $filePath)
	{
		return file_put_contents($filePath->getFullFilePath(),
			                     $filePath->getContent(),
			                     LOCK_EX);
	}

	/**
	 * Remove a file
	 * 
	 * @param  FilePath $filePath
	 * @return bool
	 */
	public function removeFile(FilePath $filePath)
	{
		return unlink($filePath->getFullFilePath());
	}

}