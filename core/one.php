<?php

/**
 * 举个例子
 */
class One
{
	public static function getOne($key)
	{
		$sql = 'SELECT one_key, one_value
				FROM one
				WHERE one_key = ? LIMIT 1';
		return DB::select($sql, [ $key ])[0];
	}

	public static function setOne($key, $value)
	{
		$sql = 'INSERT INTO one (one_key, one_value) VALUES (?, ?)
				ON DUPLICATE KEY UPDATE one_value = ?';
		$bind = [ $key, $value, $value ];
		return DB::insert($sql, $bind) >= 0;
	}

	public static function delOne($key)
	{
		$sql = 'DELETE FROM one
				WHERE one_key = ?';
		return DB::delete($sql,[ $key ]) >= 0;
	}

	public static function getOneCount()
	{
		$sql = 'SELECT count(one_key) as c
				FROM one';
		$ret = DB::select($sql);
		return $ret[0]['c'];
	}

	public static function getOneList($start = 0, $num = 10)
	{
		$sql = 'SELECT one_key, one_value
				FROM one
				LIMIT ? OFFSET ?';
		$bind = [ $num, $start ];
		return DB::select($sql, $bind);
	}
}
