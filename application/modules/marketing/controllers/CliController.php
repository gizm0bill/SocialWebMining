<?php

use App\Service\Worker,
	App\Service\Campaign\Agent\Twitter as TwitterAgent,
	App\Model\Campaign;

class Marketing_CliController extends Zend_Controller_Action
{
	public function preDispatch()
	{
//		$this->_helper->layout->disableLayout();
		set_time_limit(0);
	}

	const STATUS_RUNNING = 'running';
	const STATUS_STOPPED = 'stopped';

	/**
	 * TODO add to a service/plugin
	 */
	private function _getFullCampaignByReqId()
	{
		$c = new Campaign;
		$campaign = current( $c->fetchAllWithAttributes
		(
			$c->select()->where( Campaign::getCols()->id . " = ? ", $this->_request->getParam('id') )
		) );
		return $campaign;
	}

	public function campaignAction()
	{
		$campaign = $this->_getFullCampaignByReqId();
		if( !$campaign )
			return false;

		$worker = new Worker();
		$status = $worker->getStatus( 'marketing', 'cli', 'campaign', array( 'id' => $campaign[Campaign::getCols()->id] ) );
		if( $status == Worker::STATUS_STOPPED )
			return false;

		$controlPoint = $worker->getControlPoint( 'marketing', 'cli', 'campaign', array( 'id' => $campaign[Campaign::getCols()->id] ) );

		$twSrv = new TwitterAgent();
		$cfg = new Zend_Config_Ini( $controlPoint, null, true );
		$newCfg = new Zend_Config_Writer_Ini();

		while( true ) // run worker
		{
			$status = $worker->getStatus( 'marketing', 'cli', 'campaign', array( 'id' => $campaign[Campaign::getCols()->id] ) );
			if( $status == Worker::STATUS_STOPPED )
				return false;

			foreach( $campaign['attrs'] as $attr )
			{
				switch( $attr['attr'] )
				{
					case 'twitter_hashtag' :
						$twSrv->hashtag( $campaign[Campaign::getCols()->id], $attr['val'] );
						$jobs[] = "hashtag: #{$attr['val']}";
						break;
					case 'twitter_related_hashtag' :
						$twSrv->hashtag( $campaign[Campaign::getCols()->id], $attr['val'], true );
						$jobs[] = "related hashtag: #{$attr['val']}";
						break;
				}
			}

			$cfg->jobs = $jobs;
			$newCfg->setConfig( $cfg );
			$newCfg->write( $controlPoint );

			ob_flush();
			sleep( 180 ); // sleep one hour... or 3 minutes
		}
	}

	public function indexAction()
	{
	}

}