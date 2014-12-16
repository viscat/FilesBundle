<?php

namespace Kolekti\FilesBundle\Entity;

/**
 * Entity to describe a JsTree Node State attribute
 */
class JsTree
{
	/**
	 * List of tree nodes
	 * @var array
	 */
	private $_tree = array();

	/**
	 * Add a tree node to the list
	 * @param KolektiFilesBundleEntityJsTreeNode $jsTreeNode
	 */
	public function addTreeNode(\Kolekti\FilesBundle\Entity\JsTreeNode $jsTreeNode)
	{
		$this->_tree[] = $jsTreeNode;

		return $this;
	}

	/**
	 * Return nodes list
	 * @return array
	 */
	public function getTree()
	{
		return $this->_tree;
	}	

}