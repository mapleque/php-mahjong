<?php

class DB
{
	public static function begin()
	{
		return self::getDB()->beginTransaction();
	}
	public static function commit()
	{
		return self::getDB()->endTransaction();
	}

	public static function select($query, $bind = null)
	{
		return self::getDB()->select($query, $bind);
	}

	public static function insert($query, $bind = null)
	{
		return self::getDB()->insert($query, $bind);
	}

	public static function exec($query, $bind = null)
	{
		return self::getDB()->exec($query, $bind, false);
	}

	public static function delete($query, $bind = null)
	{
		return self::getDB()->exec($query, $bind, false);
	}

	private static function getDB()
	{
		if (self::$conn === null) {
			self::$conn = new DBConn(
				Important::DB_ADDR,
				Important::DB_USER,
				Important::DB_PASS,
				Important::DB_NAME,
				Important::DB_PORT
			);
		}
		return self::$conn;
	}

	private static $conn = null;
}
