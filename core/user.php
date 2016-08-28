<?php

/**
 * 用户
 */
class User
{
	/**
	 * 登陆
	 * @param string $username
	 * @param string $password
	 * @return int $user_id | null
	 */
	public static function login($username, $password)
	{
		$sql = 'SELECT id, password FROM user WHERE username = ? LIMIT 1';
		$user = DB::select($sql, [ $username ])[0];
		return $password === $user['password'] ? $user['id'] : null;
	}

	/**
	 * 获取用户信息
	 * @param string $username
	 */
	public static function getUserInfo($uid)
	{
		$sql = 'SELECT username FROM user WHERE id = ? LIMIT 1';
		return DB::select($sql, [ $uid ])[0];
	}
}
