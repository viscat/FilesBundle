<?php

namespace Parsingcorner\FilesBundle\Entity;

use Parsingcorner\AttributeValidationBundle\Model\AttributeValidator;

/**
 * Entity to describe a filesystem file
 */
class FileName
{
	/**
	 * File base name (without extension)
	 * @var string
	 */
	private $_baseName;

	/**
	 * File extension (without dot)
	 * @var string
	 */
	private $_extension;

    /**
     * @var AttributeValidator
     */
    private $_validator;

    function __construct()
    {
        $this->_validator = AttributeValidator::getInstance(__DIR__);
    }


    /**
	 * Set _baseName	
	 * @param string $baseName File base name (without extension)
	 * @return FileName
	 */
	public function setBaseName($baseName)
	{
		$this->_baseName = $baseName;
		$this->_validator->validateAttributes($this);

		return $this;
	}

	/**
	 * Get _baseName
	 * @return string File base name (without extension)
	 */
	public function getBaseName()
	{
		return $this->_baseName;
	}

	/**
	 * Set _extension
	 * @param string $extension File extension (without dot)
	 * @return FileName
	 */
	public function setExtension($extension)
	{
		$this->_extension = $extension;
        $this->_validator->validateAttributes($this);

		return $this;
	}

	/**
	 * Get _extension
	 * @return string File extension (without dot)
	 */
	public function getExtension()
	{
		return $this->_extension;
	}

	/**
	 * Get complete filename
	 * @return string
	 */
	public function getFullName()
	{
		return ($this->_extension) ?
					$this->_baseName . '.' . $this->_extension
					: $this->_baseName;
	}

}