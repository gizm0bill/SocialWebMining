<?php

namespace Ext\Controller\Plugin;

use Zend_Controller_Plugin_Abstract as Plugin,
	Zend_Controller_Request_Abstract as Request,
	Zend_Controller_Action_HelperBroker as HelperBroker,
	Zend_Controller_Front as Front,
	Zend_Layout;

class Layout extends Plugin
{
	/**
	 * Holds style items
	 * @var array
	 */
	private $_styles = array();

	/**
	 * The view
	 * @var Zend_View
	 */
	private $_view;

	/**
	 * The layout
	 * @var Zend_Layout
	 */
	private $_layout;

	public function dispatchLoopStartup( Request $request )
	{
		$this->_layout = Zend_Layout::getMvcInstance();
		$this->_view = $this->_layout->getView();
		$defaModule = Front::getInstance()->getDefaultModule();
		$this->_styles[$defaModule] = array
		(
			'rel' => 'stylesheet',
			'type'=>'text/css',
			'href' => $this->_view->baseUrl( "/styles/".$defaModule.".css" )
		);
		$this->_view->headLink()->prependStylesheet( $this->_styles[$defaModule] );
	}

	public function postDispatch( Request $request )
	{
		$this->_layout->setLayoutPath
		(
			APPLICATION_PATH . DIRSEP .
			"modules" . DIRSEP .
			( $module = $request->getModuleName() ) . DIRSEP .
			"views" . DIRSEP .
			"layouts"
		);
		if( !isset( $this->_styles[$module] ) )
		{
			$this->_styles[$module] = array
			(
				'rel' => 'stylesheet',
				'type'=>'text/css',
				'href' => $this->_view->baseUrl( "/styles/$module.css" )
			);
			$this->_view->headLink()->appendStylesheet( $this->_styles[$module] );
		}
	}
}