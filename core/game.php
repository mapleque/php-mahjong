<?php

class Game
{

	/**
	 * 创建游戏
	 * @return int $game_id
	 */
	public static function create()
	{
		$game_id = 0;
		return $game_id;
	}

	/**
	 * 用户参加游戏
	 * @param int $game_id
	 * @param int $user_id
	 * @return bool
	 */
	public static function attend($game_id, $user_id)
	{}

	/**
	 * 用户开始游戏
	 * @param int $game_id
	 * @param int $user_id
	 * @return bool
	 */
	public static function start($game_id, $user_id)
	{
		// 如果所有玩家都已经准备，需要置一个标记位，等待系统自动开始游戏
	}

	/**
	 * 获取游戏信息
	 * @param int $game_id
	 * @param int $user_id
	 * @return array
	 */
	public static function getGameInfo($game_id, $user_id)
	{}

	/**
	 * 检查game标记位，看是否可以开始游戏
	 * @param array $game_info
	 * @return bool
	 */
	public static function isReady($game_info)
	{}
}