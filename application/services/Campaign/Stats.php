<?php

namespace App\Service\Campaign;

use App\Model\CampaignData,
	Ext\Service\Stats as AStats;

/**
 * @todo implement
 */
class Stats extends AStats
{

	/**
	 * @var App\Model\CampaignData
	 */
	protected $_dataModel;

	public function __construct( $dataModel = null )
	{
		if( $dataModel )
		{
			$this->_dataModel = new $dataModel;
			return;
		}
		$this->_dataModel = new CampaignData;
	}

	public function write( $idCampaign, $key, $value, $time=null )
	{
		$this->_dataModel->insert( array
		(
			CampaignData::getCols()->idCampaign => $idCampaign,
			CampaignData::getCols()->attr 		=> $key,
			CampaignData::getCols()->val 		=> $value,
			CampaignData::getCols()->time 		=> $time,
		) );
	}
}