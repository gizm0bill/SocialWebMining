<?php

namespace Ext\Controller\Router;

use Zend_Auth,
	Zend_Controller_Front,
	Zend_Console_Getopt,
	Zend_Console_Getopt_Exception,
	Zend_Controller_Request_Abstract,
	Zend_Controller_Router_Abstract;

/**
 * Cli router designed to handle cli requests
 * @uses Zend_Console_Getopt
 */
class Cli extends Zend_Controller_Router_Abstract
{
	/**
	 * the auth storage params passed to constructor is set to Zend_Auth instance for ACL
	 * @see Zend_Controller_Router_Interface::route()
	 */
	public function route( Zend_Controller_Request_Abstract $dispatcher )
	{
	    try
	    {
	    	$opts = new Zend_Console_Getopt(   array
			(
	    		'action|a=s'   	 => 'action',
	    		'controller|c=s' => 'controller',
	    		'module|m=s'     => 'module',
				'params|p-s'	 => 'request parameters'
	  		) );
        	$opts->parse();

    	}
    	catch( Zend_Console_Getopt_Exception $e )
    	{
        	echo $e->getUsageMessage();
        	exit;
    	}

    	// set mvc per request
    	$front = Zend_Controller_Front::getInstance();
    	$dispatcher->setModuleName( $front->getDefaultModule() );
    	$dispatcher->setControllerName( $front->getDefaultControllerName()  );
    	$dispatcher->setActionName( $front->getDefaultAction()  );
		if( $opts->m )
			$dispatcher->setModuleName( $opts->m );
		if( $opts->c )
			$dispatcher->setControllerName( $opts->c );
		if( $opts->a )
    		$dispatcher->setActionName( $opts->a );
    	// set request params
    	parse_str( $opts->p, $reqParams );
    	$dispatcher->setParams( $reqParams );

    	// set auth
		Zend_Auth::getInstance()->getStorage()->write( (object) $this->getParam( 'auth' ) );
	}

	public function assemble( $userParams, $name = null, $reset = false, $encode = true)
	{

	}
}