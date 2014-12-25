<?php

namespace Parsingcorner\FilesBundle\Entity;

use Parsingcorner\AttributeValidationBundle\Model\AttributeValidator;

/**
 * Entity to describe a filesystem path
 */
class Path
{
	/**
	 * Complete path in string format.
	 * Must be absolute and end with slash
	 * @var string
	 */
	private $_path;

	/**
	 * Path depht in the filesystem.
	 * The range is from 1 to N where 1 is the root directory level
	 * @var integer
	 */
	private $_depth;

    /**
     * @var AttributeValidator
     */
    private $_validator;

    function __construct()
    {
        $this->_validator = AttributeValidator::getInstance(__DIR__);
    }

	/**
	 * Set path and depth in one method to proper validation
	 * 
	 * @param string $path   Complete path in string format.
	 *                       Must be absolute and end with slash.
	 * @param integer $depth Path depht in the filesystem.
	 *                       The range is from 1 to N where 1 is the root directory level
	 */
	public function setPathAndDepth($path, $depth)
	{
		$this->_setPath($path);
		$this->_setDepth($depth);
        $this->_validator->validateAttributes($this);

		return $this;
	}

	/**
	 * Set _path
	 * @param string $path Complete path in string format.
	 *                     Must be absolute and end with slash.
	 * @return Path
	 */
	private function _setPath($path)
	{
		$this->_path = $path;

		return $this;
	}

	/**
	 * Get _path
	 * @return string Complete path in string format.
	 *                Must be absolute and end with slash.
	 */
	public function getPath()
	{
		return $this->_path;
	}

	/**
	 * Set _depth
	 * @param integer $depth Path depht in the filesystem.
	 *                       The range is from 1 to N where 1 is the root directory level
	 * @return Path
	 */
	private function _setDepth($depth)
	{
		$this->_depth = $depth;

		return $this;
	}

	/**
	 * Get _depth
	 * @return integer Path depht in the filesystem.
	 *                      The range is from 1 to N where 1 is the root directory level
	 */
	public function getDepth()
	{
		return $this->_depth;
	}

}