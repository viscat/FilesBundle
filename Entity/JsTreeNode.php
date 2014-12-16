<?php

namespace Kolekti\FilesBundle\Entity;

use Kolekti\AttributeValidationBundle\Model\AttributeValidator;

/**
 * Entity to describe a JsTree Node
 */
class JsTreeNode
{
	/**
	 * Node Id
	 * @var string filePath base64 encrypted
	 */
	private $_id;

	/**
	 * Node text to label the node in the frontend
	 * @var string
	 */
	private $_text;

	/**
	 * Node content
	 * @var string
	 */
	private $_content;

	/**
	 * List of children nodes
	 * @var array
	 */
	private $_children;

	/**
	 * Node type
	 * @var string
	 */
	private $_type;

	/**
	 * Node icon
	 * @var string
	 */
	private $_icon;

	/**
	 * Node state
	 * @var Kolekti\FilesBundle\Entity\JsTreeNodeState
	 */
	private $_state;

	/**
	 * Set _id
	 * @param string $id filePath base64 encrypted
	 */
	public function setId($id)
	{
		$this->_id = $id;

		return $this;
	}

	/**
	 * Get _id
	 * @return string
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Set _text
	 * @param string $text
	 */
	public function setText($text)
	{
		$this->_text = $text;
		AttributeValidator::validateAttributes($this);

		return $this;
	}

	/**
	 * Get _text
	 * @return string
	 */
	public function getText()
	{
		return $this->_text;
	}

	/**
	 * Set _content
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->_content = $content;

		return $this;
	}	

	/**
	 * Get _content
	 * @return string
	 */
	public function getContent()
	{
		return $this->_content;
	}

	/**
	 * Set _children
	 * @param array $children
	 */
	public function setChildren($children)
	{
		$this->_children = $children;

		return $this;
	}

	/**
	 * Get _children
	 * @return array
	 */
	public function getChildren()
	{
		return $this->_children;
	}

	/**
	 * Set _type
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->_type = $type;

		return $this;
	}	

	/**
	 * Get _type
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Set _icon
	 * @param string $icon
	 */
	public function setIcon($icon)
	{
		$this->_icon = $icon;

		return $this;
	}

	/**
	 * Get _icon
	 * @return string
	 */
	public function getIcon()
	{
		return $this->_icon;
	}

	/**
	 * Set _state
	 * @param \KolektiFilesBundleEntityJsTreeNodeState $state
	 */
	public function setState(\Kolekti\FilesBundle\Entity\JsTreeNodeState $state)
	{
		$this->_state = $state;

		return $this;
	}

	/**
	 * Get _state
	 * @return \KolektiFilesBundleEntityJsTreeNodeState
	 */
	public function getState()
	{
		return $this->_state;
	}

}