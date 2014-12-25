<?php

namespace Parsingcorner\FilesBundle\Model;

use Parsingcorner\FilesBundle\Entity\Path;
use Parsingcorner\FilesBundle\Entity\FileName;
use Parsingcorner\FilesBundle\Entity\FilePath;

use Parsingcorner\FilesBundle\Exception\FilesEntitesBuilderException as Exception;

/**
 * Model to build proper file enties from strings
 */
class FilesEntitiesBuilder
{
	/**
	 * Build FileName Entity from string input
	 * @param  string $fullFileName file name to be built
	 *                              can have extension or not
	 *                              slashes (/) are forbidden
	 * @return FileName
	 */
	public function buildFileNameEntity($fullFileName)
	{
		$fileNameEntity = new FileName();

		list($baseName, $extension) = $this->_getFileNameParts($fullFileName);
		$fileNameEntity->setBaseName($baseName);
		$fileNameEntity->setExtension($extension);

		return $fileNameEntity;
	}

	/**
	 * Build Path Entity from string input
	 * @param  string $path Absolute path. Must start and end with an slash (/)
	 * @return Path
	 */
	public function buildPathEntity($path)
	{
		$this->_addSlashIfNecessary($path);
		$this->_removeDoubleSlashes($path);

		$pathEntity = new Path();
		$pathEntity->setPathAndDepth($path,
			                         $this->_calculatePathDept($path));

		return $pathEntity;
	}

	/**
	 * Build FilePath Entity from string input
	 * @param  string $filePath Complete filepath.
	 *                          The path must be absolute.
	 *                          If the last part of the string don't finish with an slash
	 *                          the last part of the string will be considered as the filename
	 * @return FilePath
	 */
	public function buildFilePath($filePath)
	{
		list($path, $fileName) = $this->_getFilePathParts($filePath);

		$pathEntity = $this->buildPathEntity($path);
		$fileNameEntity = $this->buildFileNameEntity($fileName);

		$filePathEntity = new FilePath();
		$filePathEntity->setPath($pathEntity);
		$filePathEntity->setFileName($fileNameEntity);
		$filePathEntity->setFullFilePath($pathEntity, $fileNameEntity);

		return $filePathEntity;
	}

	/**
     * Build FilePath or Path from an existent filesystem resource
     * 
     * @param  string $path Existent filesystem resource
     * @return FilePath or Path
     */
	public function buildEntityFromExistentPath($path)
	{
		if (is_file($path)) {
			$entity = $this->buildFilePath($path);
		} else if (is_dir($path)) {
			$entity = $this->buildPathEntity($path);
		} else {
			throw new Exception('The provided filepath "'
								. $path
								. '" is not an existent'
								. ' filesystem resource.');
		}

		return $entity;
	}

	/**
	 * Add and slash at the end of a string if it does not exist
	 * @param string $path
	 */
	private function _addSlashIfNecessary(&$path)
	{
		if (substr($path, -1) != DIRECTORY_SEPARATOR)
			$path .= DIRECTORY_SEPARATOR;
	}

	/**
	 * Clean double slashes
	 * @param string $path
	 */
	private function _removeDoubleSlashes(&$path)
	{
		$path = preg_replace('|'.DIRECTORY_SEPARATOR.'{2,}|',
		                     DIRECTORY_SEPARATOR,
		                     $path);
	}

	/**
	 * Calculate the path depth counting slash matches
	 * @param  string $path
	 * @return integer
	 */
	private function _calculatePathDept($path)
	{
		return substr_count($path, DIRECTORY_SEPARATOR, 1);
	}

	/**
	 * Gets filename parts. In order filename extension is not mandatory
	 * the method must be prepared to return an empty string for this part if
	 * it does not exist
	 * 
	 * @param  string $fullFileName
	 * @return array  1st position -> fileName
	 *                2nd position -> extension
	 */
	private function _getFileNameParts($fullFileName)
	{
		return (strpos($fullFileName,'.') !== false) ?
					explode('.', $fullFileName, 2)
					: array($fullFileName, '');
	}

	/**
	 * Splits filename from path from an string
	 * For this method filename and path are mandatory, so the method must
	 * throw and exception if the the regular does not found both parts
	 * 
	 * @param  string $filePath
	 * @return array 1st position -> path
	 *               2nd position -> filename
     * @throws Exception
	 */
	private function _getFilePathParts($filePath)
	{
		$lastSlashPos = strrpos($filePath, DIRECTORY_SEPARATOR) + 1;
		$path = substr($filePath, 0, $lastSlashPos);
		$fileName = substr($filePath, $lastSlashPos);

		if (!$path || !$fileName) {
			throw new Exception('The provided filepath "'
								. $filePath
								. '" is not valid. '
								. 'Remember to set an absolute '
								. 'path followed by a valid filename');
		}

		return array($path, $fileName);
	}



}