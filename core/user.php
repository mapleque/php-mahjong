<?php

/**
 * 用户
 */
class User
{
	private static $list = [
		'yangyang',
		'chenjian',
		'mother',
		'father',
	];
	/**
	 * 登陆
	 * @param string $username
	 * @param string $password
	 * @return int $user_id|null
	 */
	public static function login($username, $password)
	{
		return array_search($username) ?: null;
	}

	/**
	 * 获取用户信息
	 * @param string $username
	 */
	public static function getUserInfo($uid)
	{
		return self::$list[$uid];
	}
}
