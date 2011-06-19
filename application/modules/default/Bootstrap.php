<?php

class Research_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initNavAcl()
	{
		// the nav menu from main bootstrap
		$nav = $this->getApplication()->getResource( 'navigation' );
		/* @var $nav Zend_Navigation */

		// the acl from main bootstrap
		$acl = $this->getApplication()->getResource( 'acl' );
		/* @var $acl Zend_Acl */

	}
}