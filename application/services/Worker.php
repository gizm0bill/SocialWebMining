<?php

namespace App\Service;

use Zend_Config,
	Zend_Config_Ini,
	Zend_Config_Writer_Ini;

class Worker
{
	/**
	 * execute php command
	 * @var string
	 */
	private $_cmd = '';

	/**
	 * control point path
	 */
	private $_ctrlPointPath = '';

	const STATUS_RUNNING = 'running';
	const STATUS_STOPPED = 'stopped';

	public function __construct()
	{
		$this->_cmd = "(/usr/bin/php ".PUBLIC_PATH ."/index.php -m %s -c %s -a %s -p '%s' &) > /dev/null";
		$this->_ctrlPointPath = APPLICATION_PATH . "/../data/workers/";
	}

	/**
	 * Gets control point file name based on parameters
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @param string $args
	 * @return string
	 */
	public function getControlPoint( $module, $controller, $action, $args = null )
	{
		$ctrlPoint 	= $this->_ctrlPointPath
					. implode( "-", array( $module, $controller, $action ) ) . "-"
					. implode( "-", $args ) . ".ini";
		return $ctrlPoint;
	}

	/**
	 * Gets status of worker based on the parameters
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @param string $args
	 * @return string
	 */
	public function getStatus( $module, $controller, $action, $args = null )
	{
		$ctrlPoint = $this->getControlPoint( $module, $controller, $action, $args );
		if( file_exists( $ctrlPoint ) )
		{
			$cfg = new Zend_Config_Ini( $ctrlPoint );
			return $cfg->status;
		}
		return self::STATUS_STOPPED;
	}

	/**
	 * Gets full with jobs status of worker based on the parameters
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @param string $args
	 * @return object
	 */
	public function getFullStatus( $module, $controller, $action, $args = null )
	{
		$ctrlPoint = $this->getControlPoint( $module, $controller, $action, $args );
		$ret = array( 'status' => self::STATUS_STOPPED );
		if( file_exists( $ctrlPoint ) )
		{
			$cfg = new Zend_Config_Ini( $ctrlPoint );
			$ret['status'] = $cfg->status;
			$ret['jobs'] = $cfg->jobs;
		}
		return (object) $ret;
	}

	/**
	 * start worker
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @param string $args
	 * @return int exec error code if any
	 */
	public function start( $module, $controller, $action, $args = null )
	{
		// get control file to write to
		$ctrlPoint = $this->getControlPoint( $module, $controller, $action, $args );

		// make the command for CLI
		if( is_array( $args ) )
			$args = http_build_query( $args );
		$exec = sprintf( $this->_cmd, $module, $controller, $action, $args );

		// write the control file
		if( file_exists( $ctrlPoint ) )
		{
			$cfg = new Zend_Config_Ini( $ctrlPoint, null, true );
			$cfg->merge( new Zend_Config( array( 'status' => self::STATUS_RUNNING ) ) );
		}
		else
			$cfg = new Zend_Config( array( 'status' => self::STATUS_RUNNING ) );

		$newCfg = new Zend_Config_Writer_Ini();
		$newCfg->setConfig( $cfg );
		$newCfg->write( $ctrlPoint );

		// and do the request
		exec( $exec, $out, $return );

		return $return;
	}

	/**
	 * stop worker / send shutdown signal / put status = stopped in the control file..
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @param string $args
	 */
	public function stop( $module, $controller, $action, $args = null )
	{
		$ctrlPoint = $this->getControlPoint( $module, $controller, $action, $args );
		if( file_exists( $ctrlPoint ) )
		{
			$cfg = new Zend_Config_Ini( $ctrlPoint, null, true );
			$cfg->merge( new Zend_Config( array( 'status' => self::STATUS_STOPPED ) ) );
		}
		else
			$cfg = new Zend_Config( array( 'status' => self::STATUS_STOPPED ) );

		$newCfg = new Zend_Config_Writer_Ini();
		$newCfg->setConfig( $cfg );
		$newCfg->write( $ctrlPoint );
	}

}