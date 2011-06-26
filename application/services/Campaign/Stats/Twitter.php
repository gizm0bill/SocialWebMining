<?php

namespace App\Service\Campaign\Stats;

use App\Service\Campaign\Stats,
	App\Model\CampaignData,
	Ext\Wordnet;

class Twitter extends Stats
{
	public function statSentiments( $batch )
	{
		$wn = new Wordnet;
		$wn->senses( "star" );
	}

	public function statHashtag( $hashtag, $batch )
	{
		$res = array();
		foreach( $batch as $tweet ) // process tweets, find hastags
		{
			preg_match_all( "`#([a-zA-Z0-9_]+)`", $tweet['text'], $m );
			foreach( $m[1] as $tag )
			{
				$tag = strtolower($tag);
				if( !isset( $res[$tag] ) ) $res[$tag] = 0;
				$res[$tag]++;
			}
		}

		if( !count( $res ) ) // write zero results if the hastag is not found
		{
			$this->write
			(
				1,
				'twitter_hashtag',
				sprintf( CampaignData::getAttributeList()->twitter_hashtag, $hashtag, 0 )
			);
			return;
		}

		// sort results array and make the search hashtag first, just for safety
		asort( $res, SORT_NUMERIC );
		$res = array_reverse( $res, true );
		$head = array( $hashtag => $res[$hashtag] );
		$res = array_merge( $head, $res );

		// write key hashtag
		$this->write( 1, 'twitter_hashtag', sprintf( CampaignData::getAttributeList()->twitter_hashtag, $hashtag, current($res) ) );
		$keyHashtag = key($res);
		array_shift( $res );

		// write the rest
		foreach( $res as $relatedHashtag => $value )
			$this->write
			(
				1,
				'twitter_related_hashtag',
				sprintf( CampaignData::getAttributeList()->twitter_related_hashtag, $relatedHashtag, $keyHashtag, $value )
			);

		/*$cdata = new Model\CampaignData();
		$cdata->insert( array
		(
			Model\CampaignData::getCols()->attr => "twitter_hashtag_result_count",
			Model\CampaignData::getCols()->val	=> date( "Y-m-d H:i:s" ).",".count( $res['results'] ),
			Model\CampaignData::getCols()->idCampaign => $campaignId
		) );*/
	}

}