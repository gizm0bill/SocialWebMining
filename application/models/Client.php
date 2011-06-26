<?php

namespace App\Model;

use Ext\Db\Table as DbTable,
	Zend_Db_Table_Select;

/**
 * Main client table
 */
class Client extends DbTable
{
	protected $_name = 'client';

	protected $_primary = 'id_client';

	protected static $_mappedCols = array
	(
		'id' 	 => 'id_client',
		'idUser' => 'id_user',
		'name'	 => 'name',
		'domain' => 'domain',
		'email'	 => 'email'
	);
}