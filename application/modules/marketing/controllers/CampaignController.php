<?php

use App\Model\Campaign,
	App\Service\Campaign\Stats\Twitter as TwitterStats;

class Marketing_CampaignController extends Zend_Controller_Action
{
	private function userId()
	{
		return Zend_Auth::getInstance()->getIdentity()->id;
	}
	/**
	 * TODO add to a service
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

	public function indexAction()
	{
		$c = new Campaign;
		$this->view->campaigns =
			$c->fetchAll( $c->select()->where( Campaign::getCols()->idUser . " = ? ", $this->userId() ) );
	}

	/**
	 * Get an overview of the current statistics
	 */
	public function overviewAction()
	{
		$campaign = $this->_getFullCampaignByReqId();
		$stats = new TwitterStats();
		$hashtagStats = array();
		foreach( $campaign['attrs'] as $attr )
		{
			switch( $attr['attr'] )
			{
				case 'twitter_hashtag' :
					$hashtagStats[$attr['val']] = $stats->getHashtagStatShares( $campaign[Campaign::getCols()->id], $attr['val'] );
					break;
			}
		}
		$this->view->campaign = $campaign;
		$this->view->hashtagStats = $hashtagStats;
	}
}