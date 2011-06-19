<?php

namespace App\Model;

use Ext\Db\Table as DbTable,
	Zend_Auth_Exception,
	Zend_Auth;

class User extends DbTable
{
	protected $_name = 'user';
	protected $_primary = 'id_user';
	protected static $_mappedCols = array
	(
		"id" 		=> "id_user",
		"username" 	=> "username",
		"password"	=> "password",
		"role"		=> "role"
	);

	protected $_dependentTables = array( 'Campaign' => 'App\Model\Campaign' );

	const SALT_LENGTH 	= 12;

	private function _hashPass( $pass, $salt = null )
	{
		if( is_null( $salt ) )
        	$salt = substr( md5( uniqid( openssl_random_pseudo_bytes( self::SALT_LENGTH ), true ) ), 0, self::SALT_LENGTH );
		else
			$salt = substr( $salt, 0, self::SALT_LENGTH );
    	return $salt . sha1( $salt . $pass );
	}

	/**
	 * Login user, store in Zend_Auth storage
	 * @param string $name
	 * @param string $pass
	 * @throws Zend_Auth_Exception
	 */
	public function login( $name, $pass )
	{
		if( !( $u = $this->fetchRow( $this->select()->where( self::$_mappedCols['username'] . " = ? ", $name ) ) ) )
			throw new Zend_Auth_Exception( 'Username not found', -1 );
		if( $this->_hashPass( $pass, $u->{self::$_mappedCols['password']} ) != $u->{self::$_mappedCols['password']} )
			throw new Zend_Auth_Exception( 'Incorrect password', -3 );

		Zend_Auth::getInstance()->getStorage()->write( (object) array
		(
			self::$_mappedCols['username'] => $u->{self::$_mappedCols['username']},
			self::$_mappedCols['role'] => $u->{self::$_mappedCols['role']},
			"id" => $u->{current($this->_primary)}
		) );

		return true;
	}

	/**
	 * Register a user
	 * @param string $name
	 * @param string $pass
	 */
	public function register( $name, $pass )
	{
		return $this->insert( array
		(
			self::$_mappedCols['username'] => $name,
			self::$_mappedCols['password'] => $this->_hashPass( $pass )
		) );
	}
}