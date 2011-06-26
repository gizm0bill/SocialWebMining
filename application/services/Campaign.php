<?php

namespace App\Service;

use App\Service\Campaign\Agent\Twitter;

/**
 * the campaign service
 * @todo implement
 */
class Campaign
{
	/**
	 * @var Ext\Service\Campaign\Agent\Twitter
	 */
	private $_twitterAgent;

	public function __construct()
	{
		$this->_twitterAgent = new Twitter;
	}

	public function getTwitter()
	{
		return $this->_twitterAgent;
	}
}