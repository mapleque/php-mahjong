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
	 * @return int $user_id|null
	 */
	public static function login($username, $password)
	{
		return [
			'yangyang' => 1,
			'chenjian' => 1,
			'mother' => 1,
			'father' => 1,
		][$username] ?: null;
	}
}
