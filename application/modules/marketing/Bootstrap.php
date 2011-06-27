<?php

class Marketing_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initNavAcl()
	{
		// the nav menu from main bootstrap
		$nav = $this->getApplication()->getResource( 'navigation' );
		/* @var $nav Zend_Navigation */

		// the acl from main bootstrap
		$acl = $this->getApplication()->getResource( 'acl' );
		/* @var $acl Zend_Acl */

		$acl->addResource( new Zend_Acl_Resource( "marketing" ) );
		$acl->addResource( new Zend_Acl_Resource( "marketing:campaign" ) );
		$acl->addResource( new Zend_Acl_Resource( "marketing:cli" ) );
		$acl->addResource( new Zend_Acl_Resource( "marketing:community" ) );

		$acl->allow( 'agent', 'marketing:campaign' );
		$acl->allow( 'agent', 'marketing:community' );
		$acl->allow( 'worker', 'marketing:cli' );
	}
}