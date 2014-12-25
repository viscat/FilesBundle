<?php

namespace Parsingcorner\FilesBundle\Model;

use Parsingcorner\FilesBundle\Entity\FileName;
use Parsingcorner\FilesBundle\Entity\FilePath;
use Parsingcorner\FilesBundle\Entity\Path;
use Parsingcorner\FilesBundle\Exception\FilesHandlerException;

/**
 * CRUD model to work with filesystem
 */
class FilesHandler
{

    /**
     * FileMaster service
     * @var FileMaster
     */
    private $_fileMaster;

    /**
     * FilesEntitiesBuilder service
     * @var FilesEntitiesBuilder
     */
    private $_filesEntitiesBuilder;

    /**
     * @param FileMaster           $fileMaster
     * @param FilesEntitiesBuilder $filesEntitiesBuilder
     */
	public function __construct(FileMaster $fileMaster,
                                FilesEntitiesBuilder $filesEntitiesBuilder)
	{
        $this->_fileMaster = $fileMaster;
        $this->_filesEntitiesBuilder = $filesEntitiesBuilder;
	}

    /**
     * Build FileName Entity from string input
     * @param  string $fullFileName file name to be built
     *                              can have extension or not
     *                              slashes (/) are forbidden
     * @return FileName
     */
    public function buildFileNameEntity($fullFileName)
    {
        return $this->_filesEntitiesBuilder
                    ->buildFileNameEntity($fullFileName);
    }

    /**
     * Build Path Entity from string input
     * @param  string $path Absolute path. Must start and end with an slash (/)
     * @return Path
     */
    public function buildPathEntity($path)
    {
        return $this->_filesEntitiesBuilder
                    ->buildPathEntity($path);
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
        return $this->_filesEntitiesBuilder
                    ->buildFilePath($filePath);
    }

    /**
     * Build FilePath or Path from an existent filesystem resource
     *
     * @param  string $path Existent filesystem resource
     * @return FilePath or Path
     */
    public function buildEntityFromExistentPath($path)
    {
    	return $this->_filesEntitiesBuilder
    	            ->buildEntityFromExistentPath($path);
    }

    /**
     * BuildFilePathAndRead
     * @param  String $path
     * @return FilePath $filePath
     */
    public function buildFilePathAndRead($path)
    {
        $filePath = $this->buildFilePath($path);
        $this->readFile($filePath);

        return $filePath;
    }
	/**
	 * Create a file in the seleted path.
	 * If the file already exists or there is a problem with the file creation,
	 * the program will thrown an exception.
	 *
	 * @param FilePath $filePath
	 * @return bool Returns true if everything is ok
	 * @throws FilesHandlerException Fails if the file exists
	 *                               or file_put_contents fails
	 */
	public function createFile(FilePath &$filePath)
	{
		if ($this->_fileMaster->fileExists($filePath)
			|| $this->_fileMaster->setContent($filePath) === false
			) {
				throw new FilesHandlerException('The file "'
												. $filePath->getFullFilePath()
												. '" already exists or cannot be created. '
												. 'Use updateFile instead of createFile'
												. ' and make sure that the directory is writable.');
		}

		return true;
	}

	/**
	 * Read the content and other properties like size and last modification date
	 * from the selected file
	 * 
	 * @param  FilePath $filePath
	 * @return bool Returns true if everything is ok
	 */
	public function readFile(FilePath &$filePath)
	{
		$this->_setFileContent($filePath);
		$this->_setFileStat($filePath);

		return true;
	}

	/**
	 * Update the selected file with the selected content.
	 * If the file does not exist or there are problems to write on it,
	 * the program will thrown an exception.
	 * 
	 * @param  FilePath $filePath
	 * @return bool Returns true if everything is ok
	 * @throws FilesHandlerException Fails if the file does not exist
	 *                               or if file_put_contents fails
	 */
	public function updateFile(FilePath &$filePath)
	{
		if (!$this->_fileMaster->fileExists($filePath)
			|| $this->_fileMaster->setContent($filePath) === false
			) {
			throw new FilesHandlerException('The file "'
											. $filePath->getFullFilePath()
											. '" does not exists or cannot be written. '
											. 'Use createFile instead of updateFile'
											. ' and make sure that the file is writable.');
		}

		return true;
	}

	/**
	 * Delete a file from the filesystem and remove FilePath object instance.
	 * If the file does not exist or there are problems to remove it,
	 * the program will thrown an exception
	 * 
	 * @param  FilePath $filePath
	 * @return bool Returns true if everything is ok
	 * @throws FilesHandlerException Fails if unlink fails
	 */
	public function deleteFile(FilePath &$filePath)
	{
		if ($this->_fileMaster->removeFile($filePath)) {
			$pathEntity = $filePath->getPath();
			$fileNameEntity = $filePath->getFileName();
			unset($pathEntity);
			unset($fileNameEntity);
			unset($filePath);
		} else {
			throw new FilesHandlerException('The file "'
											. $filePath->getFullFilePath()
											. '" cannot be erased. '
											. 'Make sure that the file '
											. 'exists and is removable.');
		}

		return true;
	}

	/**
	 * Set _content attribute of a FilePath Entity
	 * @param FilePath $filePath
	 * @return bool Returns true if everything is ok
	 * @throws FilesHandlerException Fails if the file does not exists
	 * @todo Check if readfile is better for the use of read files in this application
	 */
	private function _setFileContent(FilePath &$filePath)
	{
		if (!$this->_fileMaster->fileExists($filePath)) {
			throw new FilesHandlerException('The file "'
											. $filePath->getFullFilePath()
											. '" does not exist.');
		}

		$filePath->setContent($this->_fileMaster->readFile($filePath));

		return true;
	}

	/**
	 * Maps stats() php function output to FilePath entity parameters
	 * @param FilePath $filePath
	 * @return bool Returns true if everything is ok
	 * @throws FilesHandlerException Fails if stat returns false
	 */
	private function _setFileStat(FilePath &$filePath)
	{
		$fileStat = $this->_fileMaster->getFileStats($filePath);

		if ($fileStat === false) {
			throw new FilesHandlerException('The file stats of "'
			                                . $filePath->getFullFilePath()
			                                . '" cannot be gotten properly. '
			                                . 'Make sure the file exists and '
			                                . ' it is readable.');
		}

		$filePath->setSize($fileStat['size']);
		$filePath->setLastModification(new \DateTime(date('Y-m-d H:i:s',$fileStat['mtime'])));
		$filePath->setLastAccess(new \DateTime(date('Y-m-d H:i:s',$fileStat['atime'])));
		$filePath->setOwnerId($fileStat['uid']);
		$filePath->setGroupId($fileStat['gid']);

		return true;
	}

}