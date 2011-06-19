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

    public function __construct( Zend_Auth $auth, Zend_Acl $acl )
    {
        $this->_acl		= $acl;
        $this->_auth 	= $auth;
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
			$request->setModuleName( $defaModule )->setControllerName( 'users' )->setActionName( 'login' );
    }

    /**
	 * @return Zend_Auth
	 */
    public function getAcl()
    {
    	return $this->_acl;
    }
}