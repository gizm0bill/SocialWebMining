<?php

use App\Model\Campaign;

class Marketing_CliController extends Zend_Controller_Action
{
	public function preDispatch()
	{
//		$this->_helper->layout->disableLayout();
		set_time_limit(0);
	}

	const STATUS_RUNNING = 'running';
	const STATUS_STOPPED = 'stopped';

	public function campaigntwAction()
	{
		// campaign id
		$campaignId = $this->_request->getParam('id');
		if( !$campaignId )
			return false;
		// the process data filename
		$fn = APPLICATION_PATH."/../data/workers/"
			. $this->_request->getModuleName() . "-"
			. $this->_request->getActionName() . "-"
			. $campaignId . ".ini";

		if( !file_exists( $fn ) ) // start worker
		{
			$data = "status = ".self::STATUS_RUNNING;
			file_put_contents( $fn, $data );
		}

		$twSrv = new Ext\Service\Campaign\Agent\Twitter();

		while( true ) // run worker
		{
			$cfg = new Zend_Config_Ini(  $fn );
			if( $cfg->status != self::STATUS_RUNNING )
				return false;

			if( ( $hashtag = $this->_request->getParam( 'hashtag' ) ) )
				var_dump( $twSrv->searchHashtag( $campaignId, $hashtag ) );

			ob_flush();
			sleep( 3600 ); // sleep one hour
		}
	}

	public function indexAction()
	{
		var_dump( $this->_request->getParams() );
	}

}