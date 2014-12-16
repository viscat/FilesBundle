<?php

namespace Kolekti\FilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsTreeController extends Controller
{
    /**
     * Array data to render
     * @var array
     */
    private $_renderingData = array();

    /**
     * Gets a JsTree node. The node id is received via $_GET
     * intead of a symfony handled paramether because the jsTree
     * library does not allow custom url builder and always send the id
     * request using standar get paramether syntax
     * 
     * @return JsonResponse Selected node in the format the js library understands
     */
    public function getJsTreeNodeAction()
    {
        $tree = $this->get('kolekti_filesBundle.jsTreeHandler')
                     ->listDirectory($this->getUser(), $this->_getNodeId());

        return new JsonResponse($tree);
    }

    /**
     * Gets a JsTree node content
     * 
     * @param  string $encryptedNodeId Encrypte jsTree node id
     * @return JsonResponse Selected node content in the format the js library understands
     */
    public function getJsTreeNodeContentAction($encryptedNodeId)
    {
        $nodeContent = $this->get('kolekti_filesBundle.jsTreeHandler')
                            ->getNodeContent($this->getUser(), $encryptedNodeId);

        return new JsonResponse($nodeContent);
    }

    /**
     * Default rendering for the initial page loading
     * @return HttpResponse object
     */
    public function indexAction()
    {
        return $this->_render();
    }

    /**
     * Render data
     * @return HttpResponse object
     */
    private function _render()
    {
        return $this->render('KolektiFilesBundle:JsTree:jsTree.html.twig',
            				 $this->_renderingData);
    }

    /**
     * Get the node id from the $_GET data
     * @return string Encrypted jsTree node id
     */
    private function _getNodeId()
    {
        return (isset($_GET['id']) ?
                    $_GET['id']
                    : $this->get('kolekti_security.crypt')
                           ->basicEncrypt($this->get('kolekti_filesBundle.jsTreeBasePathChar')));
    }

}
