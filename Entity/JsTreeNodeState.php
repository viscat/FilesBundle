<?php

namespace Kolekti\FilesBundle\Entity;

/**
 * Entity to describe a JsTree Node State attribute
 */
class JsTreeNodeState
{

	/**
	 * The node is opened or closed
	 * @var bool
	 */
	private $_opened;

	/**
	 * The node is abled or disabled
	 * @var bool
	 */
	private $_disabled;

	/**
	 * The node is selected or not
	 * @var bool
	 */
	private $_seleted;

	/**
	 * Set _opened
	 * @param bool $opened
	 */
	public function setOpened($opened)
	{
		$this->_opened = $opened;

		return $this;
	}

	/**
	 * Get _opened
	 * @return bool
	 */
	public function getOpened()
	{
		return $this->_opened;
	}

	/**
	 * Set _disabled
	 * @param bool $disabled
	 */
	public function setDisabled($disabled)
	{
		$this->_disabled = $disabled;

		return $this;
	}

	/**
	 * Get _disabled
	 * @return bool
	 */
	public function getDisabled()
	{
		return $this->_disabled;
	}

	/**
	 * Set _seleted
	 * @param bool $selected
	 */
	public function setSeleted($selected)
	{
		$this->_seleted = $selected;

		return $this;
	}

	/**
	 * Get _selected
	 * @return bool
	 */
	public function getSelected()
	{
		return $this->_seleted;
	}

}