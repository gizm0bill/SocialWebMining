<?php


namespace Ext\Service\Campaign;

use App\Model\CampaignData;

abstract class Stats
{
	/**
	 * @var App\Model\CampaignData
	 */
	protected $_dataModel;

	abstract function write();

	abstract function read();
}