<?php

namespace Kolekti\FilesBundle\Model;

use Kolekti\FilesBundle\Exception\DisallowFilesystemAccessException;

use Kolekti\FilesBundle\Model\FilesHandler;
use Kolekti\FilesBundle\Model\PathHandler;
use Kolekti\SecurityBundle\Model\Crypt;

use Kolekti\UserBundle\Entity\TblUsers;

use Kolekti\FilesBundle\Entity\Path;
use Kolekti\FilesBundle\Entity\FilePath;
use Kolekti\FilesBundle\Entity\JsTree;
use Kolekti\FilesBundle\Entity\JsTreeNode;
use Kolekti\FilesBundle\Entity\JsTreeNodeState;

/**
 * Object to handle jsTree library interaction
 */
class JsTreeHandler
{
	/**
	 * FilesHandler Service
	 * @var FilesHandler
	 */
	private $_filesHandler;

	/**
	 * PathHandler Service
	 * @var PathHandler
	 */
	private $_pathHandler;

	/**
	 * Crypt Service
	 * @var Crypt
	 */
	private $_crypt;

	/**
	 * List of directory fobidden to set
	 * @var array
	 */
	private $_jsTreeDirectoriesToSkip;

	/**
	 * jsTree character to get the default base path
	 * @var string
	 */
	private $_jsTreeBasePathChar;

	/**
	 * Init services
	 * 
	 * @param FilesHandler $filesHandler
	 * @param PathHandler  $pathHandler
	 * @param Crypt  $crypt
	 * @param array  $jsTreeDirectoriesToSkip
	 * @param string  $jsTreeBasePathChar
	 */
	public function __construct(FilesHandler $filesHandler,
								PathHandler $pathHandler,
								Crypt $crypt,
								array $jsTreeDirectoriesToSkip,
								$jsTreeBasePathChar)
	{
		$this->_filesHandler = $filesHandler;
		$this->_pathHandler = $pathHandler;
		$this->_crypt = $crypt;
		$this->_jsTreeDirectoriesToSkip = $jsTreeDirectoriesToSkip;
		$this->_jsTreeBasePathChar = $jsTreeBasePathChar;
	}

	/**
	 * List the selected directory
	 * 
	 * @param  TblUsers $user
	 * @param  string   $nodeId Encrypted jsTree node id
	 * @return array List of nodes in the format expected by jsTreee library
	 */
	public function listDirectory(TblUsers $user, $nodeId)
	{
		$path = $this->_getPathToRender($user, $nodeId);
		$jsTree = $this->_buildJsTree($path);
		return $this->_jsTreeToArray($jsTree);
	}

	/**
	 * Returns the file content of the selected jsTree node
	 * 
	 * @param  TblUsers $user
	 * @param  string   $nodeId Encrypted jsTree node id
	 * @return string file content
	 */
	public function getNodeContent(TblUsers $user, $nodeId)
	{
		$path = $this->_forceUserFtpAsBasePath($user, $nodeId);
		$filesystemResourceEntity = $this->_filesHandler
		                                 ->buildEntityFromExistentPath($path);

        if ($filesystemResourceEntity instanceof FilePath) {
        	$this->_filesHandler->readFile($filesystemResourceEntity);
        	$content = $filesystemResourceEntity->getContent();
        } else {
        	$content = '';
        }

		return $content;
	}

	/**
	 * Builds a JsTree object from a selected path
	 * 
	 * @param  Path   $path
	 * @return JsTree
	 */
	private function _buildJsTree(Path $path)
	{
		$jsTree = $this->_initTree();
		foreach($this->_pathHandler->scanPath($path) as $scandirItem) {
			$resourceEntity = $this->_filesHandler
			                       ->buildEntityFromExistentPath($path->getPath() . $scandirItem);

			$jsTreeNode = $this->_initTreeNode($scandirItem);
			$this->_setParamsByType($jsTreeNode, $resourceEntity);
			$this->_setNode($jsTreeNode, $jsTree);
		}

		return $jsTree;
	}

	/**
	 * Set a node if it's valid
	 * 
	 * @param JsTreeNode $jsTreeNode
	 * @param JsTree     $jsTree
	 */
	private function _setNode(JsTreeNode $jsTreeNode,
		                      JsTree &$jsTree)
	{
		if (!in_array($jsTreeNode->getText(), $this->_jsTreeDirectoriesToSkip))
			$jsTree->addTreeNode($jsTreeNode);
	}

	/**
	 * Init JsTreeNode and set basic params
	 * 
	 * @param  string $text Text to be shown in the frontend
	 * @return JsTreeNode
	 */
	private function _initTreeNode($text)
	{
		$jsTreeNode = new JsTreeNode();
		$jsTreeNode->setText($text);

		return $jsTreeNode;
	}

	/**
	 * Init JsTree
	 * 
	 * @return JsTree
	 */
	private function _initTree()
	{
		return new JsTree();
	}

	/**
	 * Set the attributes of a directory type node
	 * 
	 * @param JsTreeNode $jsTreeNode
	 * @param Path       $path
	 */
	private function _setDirectoryAttributes(JsTreeNode &$jsTreeNode,
		                                     Path $path)
	{
		$jsTreeNode->setId($this->_crypt->basicEncrypt($path->getPath()));
		$jsTreeNode->setChildren(true);
		$jsTreeNode->setType('folder');
		//$jsTreeNode->setIcon('folder');
	}

	/**
	 * Set the attributes of a file type node
	 * 
	 * @param JsTreeNode $jsTreeNode
	 * @param Path       $path
	 */
	private function _setFileAttributes(JsTreeNode &$jsTreeNode,
										FilePath $filePath)
	{
		$jsTreeNode->setId($this->_crypt->basicEncrypt($filePath->getFullFilePath()));
		$jsTreeNode->setChildren(false);
		$jsTreeNode->setType('file');
		//$jsTreeNode->setIcon('file file-'.$filePath->getFileName()->getExtension());
	}

	/**
	 * Call the method to set the attributes depending on the type of
	 * $resourceEntity object
	 * 
	 * @param JsTreeNode    $jsTreeNode
	 * @param FilePath|Path $resourceEntity
	 */
	private function _setParamsByType(JsTreeNode &$jsTreeNode,
		                              $resourceEntity)
	{
		$methodName = ($resourceEntity instanceof Path)
						? '_setDirectoryAttributes' : '_setFileAttributes';

		$this->$methodName($jsTreeNode, $resourceEntity);
	}

	/**
	 * Transforms JsTree object into an array undertable for jsTree library
	 * 
	 * @param  JsTree $jsTree
	 * @return array List of nodes in array format
	 */
	private function _jsTreeToArray(JsTree $jsTree)
	{
		$jsTreeArray = array();
		foreach ($jsTree->getTree() as $jsNode) {
			$jsTreeArray[] = $this->_nodeToArray($jsNode);
		}

		return $jsTreeArray;
	}

	/**
	 * Converts a JsTreeNode into an array
	 * 
	 * @param  JsTreeNode $node
	 * @return array Node in array format
	 */
	private function _nodeToArray(JsTreeNode $node)
	{
		return $this->_cleanUnderscoreFromKeysRecursive((array) $node);
	}

	/**
	 * Remove the underscores from the names of the attributes to be
	 * undertadable by the jsTree library. The function is recursive
	 * because some attributes could be objects, like state.
	 * 
	 * @param  array  $arrayToClean Associative array to clean keys
	 * @return array Associative array with cleaned keys
	 */
	private function _cleanUnderscoreFromKeysRecursive(array $arrayToClean)
	{
		$nodeArray = array();
		foreach ($arrayToClean as $key => $value) {
			$cleanKey = substr($key, strrpos($key, '_') + 1);
			$nodeArray[$cleanKey] = (is_object($value)) ?
				$this->_cleanUnderscoreFromKeysRecursive((array) $value) : $value;
		}

		return $nodeArray;
	}

	/**
	 * Get the path to render having in mind the user visibility
	 * 
	 * @param  TblUsers $user
	 * @param  string   $nodeId Encrypted jsTree node id
	 * 
	 * @return Path
	 */
    private function _getPathToRender(TblUsers $user, $nodeId)
    {
        $path = $this->_forceUserFtpAsBasePath($user, $nodeId);
        return $this->_filesHandler->buildPathEntity($path);
    }

    /**
     * This method obligates to show only files in the user ftp home
     * 
     * @param  string   $nodeId Encrypted jsTree node id
     * @return string Path to render
     */
    private function _forceUserFtpAsBasePath(TblUsers $user, $nodeId)
    {
    	$userHomeDir = $user->getFtpHomedir();
        if ($nodeId == $this->_jsTreeBasePathChar) {
        	$decryptedNodeId = $userHomeDir;
        } else if (!preg_match('|^'.$userHomeDir.'|', $decryptedNodeId = $this->_crypt->basicDecrypt($nodeId))) {
        	throw new DisallowFilesystemAccessException();
        }

        return $decryptedNodeId;
    }

}

?>