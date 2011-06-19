<?php

namespace Ext\Service\Campaign\Agent;

use App\Model as Model,
	Zend_Service_Twitter_Search;

class Twitter
{
	public function searchHashtag( $campaignId, $hashtag, $sinceId=null, $lang='en', $rpp=100, $geocode=null, $page=null )
	{
		$srv = new  Zend_Service_Twitter_Search('json');
		$res = $srv->search( '#'.$hashtag, array
		(
			'lang' 		=> $lang,
			//'since_id' 	=> $sinceId,
			'rpp' 		=> $rpp,
			//'geocode' 	=> $geocode,
			//'page'		=> $page
		) );
		$cdata = new Model\CampaignData();
		$cdata->insert( array
		(
			Model\CampaignData::getCols()->attr => "twitter_hashtag_result_count",
			Model\CampaignData::getCols()->val	=> date( "Y-m-d H:i:s" ).",".count( $res['results'] ),
			Model\CampaignData::getCols()->idCampaign => $campaignId
		) );
		return $res['max_id_str'];
	}
}