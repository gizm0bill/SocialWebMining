<?php

class Management_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initNavAcl()
	{
		// the nav menu from main bootstrap
		$nav = $this->getApplication()->getResource( 'navigation' );
		/* @var $nav Zend_Navigation */

		// the acl from main bootstrap
		$acl = $this->getApplication()->getResource( 'acl' );
		/* @var $acl Zend_Acl */

		// add resources
		$acl->addResource( new Zend_Acl_Resource( "management" ) );
		$acl->addResource( new Zend_Acl_Resource( "management:client" ) );
		$acl->addResource( new Zend_Acl_Resource( "management:campaign" ) );
		// add roles
		$acl->allow( 'agent', 'management:client' );
		$acl->allow( 'agent', 'management:campaign' );
	}
}