<?php

namespace Parsingcorner\FilesBundle\Entity;


use Parsingcorner\AttributeValidationBundle\Model\AttributeValidator;

/**
 * Entity to describe a filesystem filepath
 */
class FilePath
{
	/**
	 * @var FilePath
	 */
	private $_path;

	/**
	 * @var FileName
	 */
	private $_fileName;

	/**
	 * Absolute path to find a filesystem file
	 * @var string
	 */
	private $_fullFilePath;

	/**
	 * File size in Bytes
	 * @var integer
	 */
	private $_size;

	/**
	 * Last modification date
	 * @var \DateTime
	 */
	private $_lastModification;

	/**
	 * Last file acces
	 * @var \DateTime
	 */
	private $_lastAccess;

	/**
	 * Unix owner Id
	 * @var int
	 */
	private $_ownerId;

	/**
	 * Unix group Id
	 * @var int
	 */
	private $_groupId;

	/**
	 * File content
	 * @var string
	 */
	private $_content;

    /**
     * @var AttributeValidator
     */
    private $_validator;

    function __construct()
    {
        $this->_validator = AttributeValidator::getInstance(__DIR__);
    }

	/**
	 * Set _fullFilePath
	 * @param Path $path
	 * @param FileName $fileName
	 * @return FilePath
	 */
	public function setFullFilePath(Path $path, FileName $fileName)
	{
		$this->_fullFilePath = $path->getPath() . $fileName->getFullName();

		return $this;
	}

	/**
	 * Get _fullFilePath
	 * @return string Absolute path to find a filesystem file
	 */
	public function getFullFilePath()
	{
		return $this->_fullFilePath;
	}

	/**
	 * Set _path
	 * @param Path $path
	 * @return FilePath
	 */
	public function setPath(Path $path)
	{
		$this->_path = $path;

		return $this;
	}

	/**
	 * Get _path
	 * @return Path
	 */
	public function getPath()
	{
		return $this->_path;
	}

	/**
	 * Set _fileName
	 * @param FileName $fileName
	 * @return FilePath
	 */
	public function setFileName(FileName $fileName)
	{
		$this->_fileName = $fileName;

		return $this;
	}

	/**
	 * Get _fileName
	 * @return FileName
	 */
	public function getFileName()
	{
		return $this->_fileName;
	}

	/**
	 * Set _size
	 * @param integer $size File size in Bytes
	 * @return FilePath
	 */
	public function setSize($size)
	{
		$this->_size = $size;
        $this->_validator->validateAttributes($this);

		return $size;
	}

	/**
	 * Get _size
	 * @return integer File size in Bytes
	 */
	public function getSize()
	{
		return $this->_size;
	}

	/**
	 * Set _lastModification
	 * @param \DateTime $lastModification Last modification date
	 * @return FilePath
	 */
	public function setLastModification(\DateTime $lastModification)
	{
		$this->_lastModification = $lastModification;

		return $this;
	}

	/**
	 * Get _lastModification
	 * @return \DateTime Last modification date
	 */
	public function getLastModification()
	{
		return $this->_lastModification;
	}

	/**
	 * Set _lastAccess
	 * @param \DateTime $lastAccess Last file access date
	 * @return FilePath
	 */
	public function setLastAccess(\DateTime $lastAccess)
	{
		$this->_lastAccess = $lastAccess;

		return $this;
	}

	/**
	 * Get _lastAccess
	 * @return \DateTime Last file access date
	 */
	public function getLastAccess()
	{
		return $this->_lastAccess;
	}

	/**
	 * Set _ownerId
	 * @param integer $ownerId Unix file owner id
	 * @return FilePath
	 */
	public function setOwnerId($ownerId)
	{
		$this->_ownerId = $ownerId;

		return $this;
	}

	/**
	 * Get _ownerId
	 * @return integer Unix file owner id
	 */
	public function getOwnerId()
	{
		return $this->_ownerId;
	}

	/**
	 * Set _groupId
	 * @param integer $groupId Unix file group id
	 * @return FilePath
	 */
	public function setGroupId($groupId)
	{
		$this->_groupId = $groupId;

		return $this;
	}

	/**
	 * Get _groupId
	 * @return integer Unix file group id
	 */
	public function getGroupId()
	{
		return $this->_groupId;
	}

	/**
	 * Set _content
	 * @param string $content File content
	 * @return FilePath
	 */
	public function setContent($content)
	{
		$this->_content = $content;

		return $this;
	}

	/**
	 * Get _content
	 * @return string File content
	 */
	public function getContent()
	{
		return $this->_content;
	}

}