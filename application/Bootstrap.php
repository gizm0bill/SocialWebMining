<?php

use App\Model\User;
use Ext\Controller\Plugin as ExtraPlugin,
	Ext\Controller\Router as ExtraRouter;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected $_appNamespace = 'App';

	protected function _initLoader( )
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Ext');

		// TODO no hardcode the ns
		$autoloader->registerNamespace('App\Model');
		$autoloader->registerNamespace('App\Service');

		$servicesIncludePath = $this->_options['includePaths']['services'];
		$autoloader->pushAutoloader( function( $class ) use ( $servicesIncludePath )
		{
			$class = str_replace( '\\', DIRSEP, preg_replace( "`^App\\\Service`", $servicesIncludePath, $class ) ) . '.php';
			if( file_exists( $class ) )
			{
				require_once $class;
				return true;
			}
			return false;
		}, 'App\Service' );

		$modelsIncludePath = $this->_options['includePaths']['models'];
		// register custom namespace loader for models
		$autoloader->pushAutoloader( function( $class ) use ( $modelsIncludePath )
		{
			if( class_exists( $class, false ) || interface_exists( $class, false ) )
            	return;
            // basic lookup for filename
            $filePath = substr( $class, strrpos( $class, "\\" )+1 ).".php";
            if( file_exists( $modelsIncludePath . DIRSEP . $filePath ) )
            {
            	require_once $filePath;
				return;
            }

            // lookup for the base name camelcase split reduced MyModel -> My
			$oldClass = '';
			while( $class != $oldClass )
			{
				$oldClass = $class;
				$class = preg_replace( "`[A-Z][a-z]+$`", "", $class );
				$filePath = substr( $class, strrpos( $class, "\\" )+1 ).".php";
				if( !file_exists( $modelsIncludePath . DIRSEP . $filePath ) )
					continue;

				require_once $filePath;
				return;
			}
		},  'App\Model' );
		return $autoloader;
	}

	protected function _initApp()
	{
		$this->bootstrap( 'Loader' );
		$this->bootstrap( 'FrontController' );
		$front = $this->getResource( 'FrontController' );
		/* @var $front Zend_Controller_Front */

		$front->registerPlugin( new ExtraPlugin\Layout );

		if( PHP_SAPI == 'cli' )
		{
			// set cli router and auth with 'worker' role
			$front->setRouter( new ExtraRouter\Cli( array( 'auth' => array
			(
				User::getCols()->username => 'worker',
				User::getCols()->role 	  => 'worker',
				"id" => null
			) ) ) );
			$front->setParam( 'noViewRenderer', true );
			$front->returnResponse( true );
		}

		return  $front;
	}

	protected function _initNavigation()
	{
		$this->bootstrap( 'acl' );
		$acl = $this->getResource( 'acl' );
		/* @var $acl Zend_Acl */

		$this->bootstrap( 'layout' );
		$layout = $this->getResource( 'layout' );
		$view = $layout->getView();
		/* @var $view Zend_View */

		// set navigation for all modules
		$nav = new Zend_Navigation();
		$pages = array();
		$pages[] = new Zend_Navigation_Page_Mvc( array
		(
			"label" 		=> "Management Area",
			"module"		=> "management",
        	"controller"	=> "index",
			"action"		=> "index",
			"route"			=> "default",
			"pages"			=> array
			(
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Clients",
					"module"		=> "management",
		        	"controller"	=> "client",
					"action"		=> "index",
					"route"			=> "default",
				)),
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Campaigns",
					"module"		=> "management",
		        	"controller"	=> "campaign",
					"action"		=> "index",
					"route"			=> "default",
				)),
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "News",
					"module"		=> "management",
		        	"controller"	=> "news",
					"action"		=> "index",
					"route"			=> "default",
				))
			)
		));
		$pages[] = new Zend_Navigation_Page_Mvc( array
		(
			"label" 		=> "Marketing and Sales",
			"module"		=> "marketing",
        	"controller"	=> "index",
			"action"		=> "index",
			"route"			=> "default",
			"pages"			=> array
			(
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Brand Image Analysis",
					"module"		=> "marketing",
		        	"controller"	=> "brand",
					"action"		=> "index",
					"route"			=> "default",
				)),
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Campaign Evaluation",
					"module"		=> "marketing",
		        	"controller"	=> "campaign",
					"action"		=> "index",
					"route"			=> "default",
				)),
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Trend Scouting",
					"module"		=> "marketing",
		        	"controller"	=> "community",
					"action"		=> "index",
					"route"			=> "default",
				)),
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Community Detection",
					"module"		=> "marketing",
		        	"controller"	=> "community",
					"action"		=> "index",
					"route"			=> "default",
				))
			)
		));
		$pages[] = new Zend_Navigation_Page_Mvc( array
		(
			"label" 		=> "Customer Service",
			"module"		=> "customer",
        	"controller"	=> "index",
			"action"		=> "index",
			"route"			=> "default",
			"pages"			=> array
			(
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Product Recommendation",
					"module"		=> "customer",
		        	"controller"	=> "product",
					"action"		=> "index",
					"route"			=> "default",
				)),
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Customer Behavior Analysis",
					"module"		=> "customer",
		        	"controller"	=> "customer",
					"action"		=> "index",
					"route"			=> "default",
				)),
				new Zend_Navigation_Page_Mvc( array
				(
					"label" 		=> "Community extrapolation",
					"module"		=> "customer",
		        	"controller"	=> "community",
					"action"		=> "index",
					"route"			=> "default",
				))
			)
		));
		$pages[] = new Zend_Navigation_Page_Mvc( array
		(
			"label" 		=> "Contact and Support",
			"module"		=> "research",
        	"controller"	=> "index",
			"action"		=> "contact",
			"route"			=> "default",
		));
		$nav->addPages( $pages );
		$view->navigation( $nav );
		return $nav;
	}

	protected function _initAcl()
	{
		$acl = new Zend_Acl();
		// add our application roles for all modules
		$acl->addRole( new Zend_Acl_Role( 'guest' ) );
		$acl->addRole( new Zend_Acl_Role( 'agent' ), 'guest' );
		$acl->addRole( new Zend_Acl_Role( 'manager' ), 'agent' );
		$acl->addRole( new Zend_Acl_Role( 'worker' ), 'manager' );
		$acl->addRole( new Zend_Acl_Role( 'root' ), 'manager' );

		$acl->addResource( new Zend_Acl_Resource( "error" ) );
		$acl->addResource( new Zend_Acl_Resource( "users" ) );
		$acl->addResource( new Zend_Acl_Resource( "index" ) );

		$acl->allow( "agent", 'index' );

		// and allow root on everything
		$acl->allow( 'root' );
		return $acl;
	}

	protected function _initAuth()
	{
		$this->bootstrap( 'acl' );
		$auth = Zend_Auth::getInstance();
        if( !$auth->hasIdentity() || $auth->getStorage()->read()->role == null )
        {
        	$auth->getStorage()->read()->role = 'guest';
        	$auth->getStorage()->write( $auth->getStorage()->read() );
        }
        $this->getResource( 'FrontController' )->registerPlugin( new ExtraPlugin\Auth( $auth, $this->getResource( 'acl' ) ) );
	}
}

