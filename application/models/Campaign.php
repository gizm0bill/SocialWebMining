<?php

namespace App\Model;

use Ext\Db\Table as DbTable,
	Zend_Db_Table_Select;

/**
 * Main campaign table
 */
class Campaign extends DbTable
{
	protected $_name = 'campaign';

	protected $_primary = 'id_campaign';

	protected $_dependentTables = array( 'Attributes' => 'App\Model\CampaignAttributes' );

	protected static $_mappedCols = array
	(
		'id' 	=> 'id_campaign',
		'idUser' => 'id_user',
		'title' => 'title',
		'from'	=> 'from',
		'to'	=> 'to'
	);

	protected static $_attrs = array
	(
		'twitter_hashtag' => 'Twitter hashtag',
		'twitter_related_hashtag' => 'Twitter related hashtag',
		'twitter_replyto' => 'Twitter in reply to'
	);

	public static function getAttributeList()
	{
		return self::$_attrs;
	}

	/**
	 *
	 * fetches all campaigns with their attributes
	 * @param string|array|Zend_Db_Table_Select $where
	 * @param string|array $order
	 * @param int $count
	 * @param int $offset
	 */
	public function fetchAllWithAttributes( $where = null, $order = null, $count = null, $offset = null )
	{
		$campaignRows = $this->fetchAll( $where, $order, $count, $offset );
		$ret = array();
		foreach( $campaignRows as $row )
		{
			/* @var $row Ext\Db\Table\Row */
			$attrs = $row->findAttributes(); // magic function for fetching dependent rows
			$filteredAttrs = array();
			foreach( $attrs as $attr )
			{
				$attrTable = $attr->getTable();
				$cols = $attrTable::getCols();
				if( !in_array( $attr->{$cols->attr}, array_keys( self::$_attrs ) ) )
				{
					// cleanup invalid attribute
					$attr->delete();
					continue;
				}
				$filteredAttrs[] = $attr->toArray();
			}
			$ret[] = array_merge( $row->toArray(), array( 'attrs' => $filteredAttrs ) );
		}
		return $ret;
	}

	public function add($data)
	{
		$attrs = array();
		if( isset( $data['attrs'] ) )
		{
			$attrs = $data['attrs'];
			unset( $data['attrs'] );
			$campaignAttr = new CampaignAttributes();
		}
		$campaignId = $this->insert($data);

		foreach( $attrs as $attr )
			$campaignAttr->insert( array_merge( $attr, array( $campaignAttr->getCols()->idCampaign => $campaignId ) ) );

		return $campaignId;
	}
}

/**
 * Campaign attributes
 */
class CampaignAttributes extends DbTable
{
	protected $_name = 'campaign_attr';

	protected $_primary = array( 'id_campaign', 'attr', 'val' );

	protected static $_mappedCols = array
	(
		'idCampaign' => 'id_campaign',
		'attr' 		 => 'attr',
		'val'		 => 'val'
	);

	protected $_referenceMap    = array
	(
        'Campaign' => array
		(
            'columns'           => 'id_campaign',
            'refTableClass'     => 'App\Model\Campaign',
            'refColumns'        => 'id_campaign'
        ),
    );
}

/**
 * Campaign mining data
 */
class CampaignData extends DbTable
{
	protected $_name = 'campaign_data';

	protected $_primary = array( 'id_campaign', 'attr', 'val' );

	protected static $_mappedCols = array
	(
		'idCampaign' => 'id_campaign',
		'attr' 		 => 'attr',
		'val'		 => 'val',
		'time'		 => 'time'
	);

	/**
	 * attributes ment to define how to store data
	 * 		twitter_hashtag = hashtag, count
	 * 		twitter_related_hashtag = related hashtag, hashtag, count
	 *		twitter_agent_hashtag_lastid = hashtag, tweet id
	 *
	 * warning: mind the replacement types in the format cause it can break on huge numbers for example
	 * @var array
	 */
	protected static $_attrs = array
	(
		'twitter_hashtag' 				=> '%s,%d',
		'twitter_related_hashtag' 		=> '%s,%s,%d',
		'twitter_related_hashtag_percent' => '%s,%s,%f',
		'twitter_agent_hashtag_lastid' 	=> '%s,%s',
		'twitter_hashtag_community' 	=> '%s,%s,%s',
		'twitter_hashtag_related_comunity' => '%s,%s,%s'
	);

	/**
	 * @see self::$_attrs
	 */
	public static function getAttributeList()
	{
		return (object) self::$_attrs;
	}

	/**
	 *
	 * Enter description here ...
	 * @param int $campaignId
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
     * @param int                               $count  OPTIONAL An SQL LIMIT count.
     * @param int                               $offset OPTIONAL An SQL LIMIT offset.
     * @return Ext\Db\Table
	 */
	public function fetchAllForCampaign( $campaignId, $where = null, $order = null, $count = null, $offset = null )
	{
		$select = $this->select();
		if( $where instanceof Zend_Db_Table_Select )
			$select = $where;
		else
			$select->where( $where );
		$select->where( self::getCols()->idCampaign . "= ?", $campaignId );
		$select->order( $order );
		$select->limit( $count, $offset );

		$campaignRows = $this->fetchAll( $select );
		return $campaignRows;
	}
}