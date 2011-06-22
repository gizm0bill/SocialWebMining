<?php

namespace Ext\Service\Campaign;

use Ext\Service\Agent as AbstractAgent,
	Ext\Service\Campaign\Agent\Twitter;

class Agent extends AbstractAgent
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