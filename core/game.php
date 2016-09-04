<?php

class Game
{

	/**
	 * 创建游戏
	 * @return int $game_id | null
	 */
	public static function create($user_id, $member = 1)
	{
		DB::begin();
		$sql = 'INSERT INTO game (member, set_seq, game_seq, time)
				VALUES (?,?,?,NOW())';
		$game_id = DB::insert($sql, [ $member, SEQ_EAST, SEQ_EAST ]);
		if ($game_id <= 0) {
			DB::commit(false);
			return null;
		}
		$sql = 'INSERT INTO user_log
					(user_id, game_id, set_id, seq, ops, status, time)
				SELECT ?,?,NULL,count(*),?,?,NOW() FROM user_log
				WHERE game_id = ? LIMIT 1';
		$bind = [ $user_id, $game_id, OP_INIT , SLS_CREATE, $game_id ];
		$user_log_id = DB::insert($sql, $bind);
		if ($user_log_id <= 0) {
			DB::commit(false);
			return null;
		}
		DB::commit(true);
		return $game_id;
	}

	/**
	 * 用户参加游戏
	 * @param int $game_id
	 * @param int $user_id
	 * @return bool
	 */
	public static function attend($game_id, $user_id)
	{
		DB::begin();
		$sql = 'SELECT member FROM game WHERE id = ? LIMIT 1';
		$game_info = DB::select($sql, [ $game_id ])[0];
		$sql = 'SELECT count(*) AS c FROM user_log WHERE game_id = ? LIMIT 1';
		if (DB::select($sql, [ $game_id ])[0]['c'] >= $game_info['member']) {
			DB::commit(false);
			return false;
		}
		$sql = 'INSERT INTO user_log
					(user_id, game_id, set_id, seq, ops, status, time)
				SELECT ?,?,NULL,count(*),?,?,NOW() FROM user_log
				WHERE game_id = ? LIMIT 1';
		$bind = [ $user_id, $game_id, OP_INIT , SLS_CREATE, $game_id ];
		$user_log_id = DB::insert($sql, $bind);
		if ($user_log_id <= 0) {
			DB::commit(false);
			return null;
		}
		DB::commit(true);
		return true;
	}

	/**
	 * 用户开始游戏
	 * @param int $user_id
	 * @return bool
	 */
	public static function start($user_id)
	{
		// 如果所有玩家都已经准备，需要置一个标记位，等待系统自动开始游戏
		$sql = 'UPDATE user_log SET status = ?
				WHERE user_id = ? && status = ? LIMIT 1';
		return DB::update($sql, [ SLS_READY, $user_id, SLS_CREATE ]) === 1;
	}

	/**
	 * 获取游戏信息
	 * @param int $user_id
	 * @return array
	 */
	public static function getGameInfo($user_id)
	{
		$sql = 'SELECT game_id, set_id, member FROM user_log
				INNER JOIN game ON game.id = game_id
				WHERE user_id = ? ORDER BY user_log.id DESC LIMIT 1';
		$game_info = DB::select($sql, [ $user_id ])[0];
		if (!isset($game_info)) {
			return [];
		}
		$sql = 'SELECT username FROM user WHERE id IN (
					SELECT user_id FROM user_log WHERE game_id = ?
				)';
		$game_info['user_list'] = DB::select($sql, [ $game_info['game_id'] ]);
		return $game_info;
	}

	/**
	 * 检查game标记位，看是否可以开始游戏
	 * @param array $game_info
	 * @return bool
	 */
	public static function isReady($game_info)
	{
		$sql = 'SELECT count(*) AS c FROM user_log
				WHERE game_id = ? && status = ? LIMIT 1';
		$ready_count = DB::select($sql, [
			$game_info['game_id'], SLS_READY ])[0]['c'];
		return $ready_count === $game_info['member'];
	}
}
