<?php

class Game
{

	/**
	 * 创建游戏
	 * @return int $set_log_id | null
	 */
	public static function create($user_id, $member = 4)
	{
		$sql = 'INSERT INTO game (member, time) VALUES (?,NOW())';
		$game_id = DB::insert($sql, [ $member ]);
		$sql = 'INSERT INTO set_info (time) VALUES (NOW())';
		$set_id = DB::insert($sql);
		$sql = 'INSERT INTO set_log (user_id, game_id, set_id, status, time) VALUES (?,?,?,?,NOW())';
		$set_log_id = DB::insert($sql, [ $user_id, $game_id, $set_id, SLS_CREATE ]);
		return $set_log_id > 0 ? $set_log_id : null;
	}

	/**
	 * 用户参加游戏
	 * @param int $game_id
	 * @param int $user_id
	 * @return bool
	 */
	public static function attend($game_id, $user_id)
	{
		$sql = 'SELECT member FROM game WHERE id = ? LIMIT 1';
		$game_info = DB::select($sql, [ $game-id ])[0];
		$sql = 'SELECT count(*) AS c FROM set_log WHERE game_id = ? LIMIT 1';
		if (DB::select($sql, [ $game_id ])[0]['c'] >= $game_info['member']) {
			return false;
		}
		$sql = 'INSERT INTO set_log (user_id, game_id, set_id, status, time) VALUES (?,?,?,?,NOW())';
		$set_log_id = DB::insert($sql, [ $user_id, $game_id, $set_id, SLS_CREATE ]);
		if ($set_log_id > 0) {
			return true;
		}
	}

	/**
	 * 用户开始游戏
	 * @param int $game_id
	 * @param int $user_id
	 * @return bool
	 */
	public static function start($set_log_id, $user_id)
	{
		// 如果所有玩家都已经准备，需要置一个标记位，等待系统自动开始游戏
		$sql = 'UPDATE set_log SET status = ? WHERE id = ? && user_id = ? LIMIT 1';
		return DB::update($sql, [ SLS_READY, $set_log_id, $user_id ])[0];
	}

	/**
	 * 获取游戏信息
	 * @param int $user_id
	 * @return array
	 */
	public static function getGameInfo($user_id)
	{
		$sql = 'SELECT game_id, set_id, member FROM set_log
				INNER JOIN game ON game.id = game_id,
				INNER JOIN set_info ON set_info.id = set_id
				WHERE user_id = ? ORDER BY set_log.id DESC LIMIT 1';
		$game_info = DB::select($sql, [ $user_id ])[0];
		if (!isset($game_info)) {
			return [];
		}
		$sql = 'SELECT username FROM user WHERE id IN (
					SELECT user_id FROM set_log WHERE set_id = ?
				)';
		$game_info['user_list'] = DB::select($sql, [ $game_info['set_id'] ]);
		return $game_info;
	}

	/**
	 * 检查game标记位，看是否可以开始游戏
	 * @param array $game_info
	 * @return bool
	 */
	public static function isReady($game_info)
	{
		$sql = 'SELECT count(*) AS c FROM set_log WHERE gamae_id = ? && set_id = ? && status = ? LIMIT 1';
		$ready_count = DB::select($sql, [ $game_info['game_id'], $game_info['set_id'], SLS_READY ])[0]['c'];
		return $ready_count === $game_info['member']
	}
}
