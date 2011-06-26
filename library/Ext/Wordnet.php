<?php

namespace Ext;

class Wordnet
{
	/**
	 * path to wordnet command
	 * @var string
	 */
	private $_wnPath = "wn"; //usr/bin/wn

	// arguments list
	const ARG_HYPERNYMS = 'hype%s';
	const ARG_HYPONYMS 	= 'hypo%s';
	const ARG_ANTONYMS 	= 'ants%s';
	const ARG_SYNONYMS 	= 'syns%s';

	const WORD_TYPE_NOUN = 'noun';
	const WORD_TYPE_VERB = 'verb';
	const WORD_TYPE_ADJ	= 'adj';
	const WORD_TYPE_ADV	= 'adv';

	/**
	 * mapped word type letters usually used in command args
	 * @var array
	 */
	private $_wordTypes = array
	(
		"n" => self::WORD_TYPE_NOUN,
		"v" => self::WORD_TYPE_VERB,
		"a" => self::WORD_TYPE_ADJ,
		"r" => self::WORD_TYPE_ADV
	);

	public function senses( $word )
	{
		$wnData = '';
		$returnData = array();
		foreach( ( $types = array( 'n', 'v', 'a', 'r' ) ) as $type )
		{
			$cmd = array
			(
				$this->_wnPath,
				$word,
				"-".sprintf( self::ARG_SYNONYMS, $type )
			);
			exec( implode( " ", $cmd ), $wnData );
			$k = 0;
			$returnData[ $this->_wordTypes[$type] ] = array();
			while( true )
			{
				preg_match( "`^Sense\s\d+`", current($wnData), $m );
				if( count( $m ) )
				{
					$val = trim( next( $wnData ) );
					$key = trim( preg_replace( "`=>`", '', next( $wnData ) ) );
					$returnData[ $this->_wordTypes[$type] ][$key] = $val;
					continue;
				}
				if( next($wnData) === false ) break;
			}
		}
		return $wnData;
	}
}