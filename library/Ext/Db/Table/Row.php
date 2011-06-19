<?php

namespace Ext\Db\Table;

use Zend_Db_Table_Row_Abstract,
	Zend_Loader_Autoloader;
/**
 * @see Ext\Db\Table
 */
class Row extends Zend_Db_Table_Row_Abstract
{
	/**
	 * get dependent by alias from dependency map
	 * @see Zend_Db_Table_Row_Abstract::findDependentRowset()
	 */
	public function findDependentRowset( $dependentTable, $ruleKey = null, Zend_Db_Table_Select $select = null)
	{
		$dependentTables = $this->_table->getDependentTables();
		if( isset( $dependentTables[$dependentTable] ) )
			return parent::findDependentRowset( $dependentTables[$dependentTable], $ruleKey, $select );
		return parent::findDependentRowset( $dependentTable, $ruleKey, $select );
	}

	/**
	 * find parent from reference map usin fully qualified namespace in refTableClass
	 * @see Zend_Db_Table_Row_Abstract::findParentRow()
	 */
	public function findParentRow( $parentTable, $ruleKey = null, Zend_Db_Table_Select $select = null)
	{
		$ns = preg_replace( "`\\w+$`", "", $this->_tableClass );
		return parent::findParentRow( $ns.$parentTable, $ruleKey, $select );
	}

	/**
	 * use autoloader for this rather than what they did
	 * @see Zend_Db_Table_Row_Abstract::_getTableFromString()
	 */
	protected function _getTableFromString( $tableName )
	{
		Zend_Loader_Autoloader::getInstance()->autoload($tableName);
		return new $tableName;
	}
}