<?php
namespace Debug\Toolbar\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Debug.Toolbar".              *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Debug\Toolbar\Annotations as Debug;

/**
 * Profile controller for the Debug.Toolbar package 
 *
 * @FLOW3\Scope("singleton")
 */
class ProfileController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$dataRenderers = array();
		foreach($this->reflectionService->getAllImplementationClassNamesForInterface('Debug\Toolbar\Debugger\DebuggerInterface') as $dataRendererClass) {
			$dataRenderer = new $dataRendererClass();
			$dataRenderers[$dataRenderer->getName()] = $dataRenderer;
		}
		$this->view->assign("dataRenderers", $dataRenderers);

		if($this->request->hasArgument("token")){
			$this->view->assign("token", $this->request->getArgument("token"));
			\Debug\Toolbar\Service\DataStorage::load($this->request->getArgument("token"));
			\Debug\Toolbar\Service\DataStorage::freeze();
		}

		if($this->request->hasArgument("renderer")){
			$this->view->assign("currentRenderer", $dataRenderers[$this->request->getArgument("renderer")]);
		}else{
			$firstRenderer = reset($dataRenderers);
			$this->view->assign("currentRenderer", $firstRenderer);
		}
	}

}

?>