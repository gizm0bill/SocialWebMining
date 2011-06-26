<?php

namespace App\Service;

/**
 * @todo implement
 */
abstract class Stats
{
	/**
	 * @var App\Model\CampaignData
	 */
	protected $_dataModel;

	abstract function write();

	abstract function read( $data );
}