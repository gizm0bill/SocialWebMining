<?php

namespace Ext\Controller\Plugin;

use Zend_Controller_Plugin_Abstract as Plugin,
	Zend_Acl,
	Zend_Auth,
	Zend_Controller_Request_Abstract,
	Zend_Controller_Front
;

class Auth extends Plugin
{
	/**
	 * @var Zend_Auth
	 */
	private $_auth  = null;

	/**
	 * @var Zend_Acl
	 */
	private $_acl 	= null;

	/**
	 * default actions if not logged in
	 * @var array ( module, controller, action )
	 */
	private $_defaults = null;

	/**
	 * @param Zend_Auth $auth
	 * @param Zend_Acl $acl
	 * @param array $defaults default action to go to if no access
	 */
    public function __construct( Zend_Auth $auth, Zend_Acl $acl, $defaults )
    {
        $this->_acl		= $acl;
        $this->_auth 	= $auth;
        $this->_defaults = $defaults;
    }

    public function routeShutdown( Zend_Controller_Request_Abstract $request )
    {
    	$defaModule = Zend_Controller_Front::getInstance()->getDefaultModule();
        $resource 	= ( ( $curModule = $request->getModuleName() ) != $defaModule ? "$curModule:" : "" ) . $request->getControllerName();
        $action 	= $request->getActionName();
        $role 		= @$this->_auth->getStorage()->read()->role;
//		var_dump( $this->_acl->get( $resource ) );die;
//		var_dump( $role, $resource, $action );
//		var_dump( $this->_acl->isAllowed( $role, $resource, $action ) );die;
        $baseResource = explode( ":", $resource );
        $baseResource = $baseResource[0];
		if( !$this->_acl->isAllowed( $role, $baseResource, $action )
			&& !$this->_acl->isAllowed( $role, $resource, $action ) )
		{
			if( !$this->_defaults['module'] )
				$this->_defaults['module'] = $defaModule;
			$request
				->setModuleName( $this->_defaults['module'] )
				->setControllerName( $this->_defaults['controller'] )
				->setActionName( $this->_defaults['action'] );
		}
    }

    /**
	 * @return Zend_Auth
	 */
    public function getAcl()
    {
    	return $this->_acl;
    }
}