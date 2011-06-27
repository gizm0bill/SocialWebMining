<?php

namespace App\Service\Campaign\Agent;

use App\Model\CampaignData,
	App\Model\CampaignAttributes,
	App\Service\Campaign\Stats\Twitter as CampaingStatsTwitter,
	Ext\Service\Agent\Twitter as TwitterAgent,
	Ext\Wordnet,
	Zend_Service_Twitter_Search,
	Zend_Db_Select,
	App\Model\Campaign;

class Twitter extends TwitterAgent
{
	/* related hashtag low percentage bound to figure if start new search for */
	const HASHTAG_RELATED_LOW_PERC = 0.05;
	/* related hashtag high percentage bound to figure if start search for */
	const HASHTAG_RELATED_HIGH_PERC = 0.3;
	/* related hashtag word similarity for decision */
	const HASHTAG_RELATED_LOW_SIMIL = 0.05;

	/**
	 * TODO add to a service
	 */
	private function _getFullCampaignById( $idCampaign )
	{
		$c = new Campaign;
		$campaign = current( $c->fetchAllWithAttributes
		(
			$c->select()->where( Campaign::getCols()->id . " = ? ", $idCampaign )
		) );
		return $campaign;
	}

	public function hashtag( $idCampaign, $hashtag )
	{
		$s = new CampaingStatsTwitter;
		$shares = $s->getHashtagStatShares( $idCampaign, $hashtag );
		array_shift( $shares ); // take first hashtag which is the key one out

		$c = $this->_getFullCampaignById($idCampaign);
		$ca = new CampaignAttributes;
		foreach( $shares as $relHashtag => $share )
		{
			if( $share > self::HASHTAG_RELATED_LOW_PERC && $share < self::HASHTAG_RELATED_HIGH_PERC)
			{
				$wn = new Wordnet();
				$simil = json_decode( $wn->getSimilarity( $hashtag, $relHashtag ) );
				if( $simil[0] > self::HASHTAG_RELATED_LOW_SIMIL )
				{
					foreach( $c['attrs'] as $attr )
						if( ( $attr['attr'] == 'twitter_hashtag' || $attr['attr'] == 'twitter_related_hashtag' )
							&& $attr['val'] == $relHashtag )
							continue(2);

					$ca->insert( array
					(
						CampaignAttributes::getCols()->attr => 'twitter_related_hashtag',
						CampaignAttributes::getCols()->val => $relHashtag,
						CampaignAttributes::getCols()->idCampaign => $idCampaign
					));
				}
			}
		}

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
			'since_id' 	=> $lastId, 'rpp' => 100
		));

		// send it to the stater
		$s->statHashtag( $idCampaign, $hashtag, $data->results );

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