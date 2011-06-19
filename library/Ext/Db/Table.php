<?php

namespace Ext\Db;

use Zend_Db_Table_Abstract;

/**
 * Modified table to use custom row class and accept
 * fully qualified namespaces for table relations;
 * thus:
 * 		protected $_dependentTables = array( 'ModelAlias' => 'My\ModelNs\Model' )
 * 		and shall use $row->findModelAlias()
 * 		&&
 * 		protected $_referenceMap = array( 'Rule' => array( 'refTableClass' => 'My\ModelNs\Model', ... ) )
 * 		and shall use $row->findParentModel()
 */
abstract class Table extends Zend_Db_Table_Abstract
{
	protected $_rowClass = 'Ext\Db\Table\Row';

	protected static $_mappedCols = null;

	/**
	 * method to get user mapped cols based on table map
	 * (late static binding)
	 */
	public static function getCols()
	{
		return (object) static::$_mappedCols;
	}
}