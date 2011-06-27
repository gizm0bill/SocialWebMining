<?php

namespace App\Service\Campaign\Stats;

use App\Service\Campaign\Stats,
	App\Model\CampaignData,
	Ext\Wordnet,
	Zend_Db_Select;

class Twitter extends Stats
{
	public function statSentiments( $batch )
	{
		//$wn = new Wordnet;
		//$wn->senses( "star" );
	}

	public function statHashtag( $idCampaign, $hashtag, $batch )
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
				$idCampaign,
				'twitter_hashtag',
				sprintf( CampaignData::getAttributeList()->twitter_hashtag, $hashtag, 0 )
			);
			return;
		}

		// sort results array and make the search hashtag first, just for safety
		asort( $res, SORT_NUMERIC );
		$res = array_reverse( $res, true );
		$head = array( $hashtag => $res[$hashtag] );
		$hashtagCount = $res[$hashtag]; // key hashtag count
		$res = array_merge( $head, $res );

		// write key hashtag
		$this->write( $idCampaign, 'twitter_hashtag', sprintf( CampaignData::getAttributeList()->twitter_hashtag, $hashtag, current($res) ) );
		$keyHashtag = key($res);
		array_shift( $res );

		// write the rest
		foreach( $res as $relatedHashtag => $value )
		{
			if( strlen( $relatedHashtag ) < 2 ) continue;

			$this->write
			(
				$idCampaign,
				'twitter_related_hashtag',
				sprintf( CampaignData::getAttributeList()->twitter_related_hashtag, $keyHashtag, $relatedHashtag, $value )
			);
			$relatedPercent = $value / $hashtagCount;
			$this->write
			(
				$idCampaign,
				'twitter_related_percent',
				sprintf( CampaignData::getAttributeList()->twitter_related_hashtag_percent, $keyHashtag, $relatedHashtag, $relatedPercent )
			);
		}
	}

	public function getHashtagStatShares( $idCampaign, $hashtag )
	{
		$cd = new CampaignData;
		$select = $cd->select();
		// selecting data based on hashtag, sorted ASCENDING to make the calculations correctly
		$all = $cd->fetchAll
		(
			$select
				->where( CampaignData::getCols()->attr . " LIKE 'twitter_%' " )
				->where( CampaignData::getCols()->val . " LIKE '$hashtag%' " )
				->where( CampaignData::getCols()->idCampaign . " = ? ", $idCampaign ),
			$select->order( CampaignData::getCols()->time .' '. Zend_Db_Select::SQL_ASC )
		);

		// calculate share parts for each segment of data
		$share = $relatedShare = $totalResults = array();
		foreach( $all as $row )
		{
			if( $row->{CampaignData::getCols()->attr} == 'twitter_hashtag' )
			{
				$tagVal = explode( ",", $row->{CampaignData::getCols()->val} );
				$share[] = $currentShare = $tagVal[1];
			}
			if( $row->{CampaignData::getCols()->attr} == 'twitter_related_percent' )
			{
				$relatedTagVal = explode( ",", $row->{CampaignData::getCols()->val} );
				if( strlen( $relatedTagVal[1] ) < 2 ) continue;
				$relatedShare[$relatedTagVal[1]][] = $relatedTagVal[2] * $currentShare;
			}
		}
		$totalResults[$hashtag] = array_sum( $share );
		foreach( $relatedShare as $k => $v )
			$totalResults[$k] = array_sum( $v ) / $totalResults[$hashtag];

		return $totalResults;
	}

}