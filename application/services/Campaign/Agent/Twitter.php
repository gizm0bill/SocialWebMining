<?php

namespace App\Service\Campaign\Agent;

use App\Model\CampaignData,
	App\Service\Campaign\Stats\Twitter as CampaingStatsTwitter,
	Ext\Service\Agent\Twitter as TwitterAgent,
	Zend_Service_Twitter_Search,
	Zend_Db_Select;

class Twitter extends TwitterAgent
{
	public function hashtag( $idCampaign, $hashtag )
	{
		$cd = new CampaignData;

		// find last id value in the db for the campaign and searched hashtag
		$select = $cd->select();
		$row = $cd->fetchRow
		(
			$select
				->where( CampaignData::getCols()->attr . " = 'twitter_agent_hashtag_lastid' " )
				->where( CampaignData::getCols()->val . " LIKE '$hashtag%' " )
				->where( CampaignData::getCols()->idCampaign . " = ? ", $idCampaign ),
			$select->order( CampaignData::getCols()->time .' '. Zend_Db_Select::SQL_DESC )
		);
		$lastId = 0;
		if( $row )
		{
			$lastId = explode( ",", $row->val );
			$lastId = $lastId[1];
		}

		// make the actual search
		$srv = new  Zend_Service_Twitter_Search('json');
		$data = (object) $srv->search( '#'.$hashtag, array
		(
			'since_id' 	=> $lastId
		));

		// send it to the stater
		$s = new CampaingStatsTwitter;
		$s->statHashtag( 'wikileaks', $data->results );

		// and insert into the db the last searched hastag id
		$cd->insert( array
		(
			CampaignData::getCols()->idCampaign => $idCampaign,
			CampaignData::getCols()->attr 		=> 'twitter_agent_hashtag_lastid',
			CampaignData::getCols()->val		=>
				sprintf( CampaignData::getAttributeList()->twitter_agent_hashtag_lastid, $hashtag, $data->max_id_str )
		));
	}
}