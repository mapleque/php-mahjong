<?php

class Set
{
	/**
	 * 创建一盘
	 * @param int $game_id
	 * @param int $user_id
	 * @return bool
	 */
	public static function start($game_id)
	{
		$rule = Mahjong::getRule('Standard');
		$total_card = $rule->getTotalCard();
		DB::begin();
		$sql = 'INSERT INTO set_info (total_card, cur_seq, time) SELECT ?, seq, NOW() FROM game WHERE id = ? LIMIT 1';
		$set_id = DB::insert($sql, [ json_encode($total_card), $game_id ]);
		if ($set_id <= 0) {
			DB::commit(false);
			return false;
		}
		$sql = 'UPDATE user_log SET set_id = ? WHERE game_id = ?';
		if (DB::update($sql, [ $set_id, $game_id ]) <= 0) {
			DB::commit(false);
			return false;
		}
		DB::commit(true);
		return true;
	}

	/**
	 * 获取盘信息
	 * @param int $set_id
	 * @param int $user_id
	 * @return array
	 */
	public static function getSetInfo($user_id)
	{
		$sql = 'SELECT set_id, game_id FROM user_log
				INNER JOIN set_info ON set_id = set_info.id
				WHERE user_id = ? && status = ? LIMIT 1';
		$set_info = DB::select($sql, [ $user_id, SLS_READY ])[0];
		$sql = 'SELECT user_id, status, hand, pool, waiting, win_info FROM user_log WHERE game_id = ? && status = ?';
		$log_info = DB::select($sql, [ $set_info['game_id'], SLS_READY ]);
		$set_info['log_info'] = $log_info;
		return $set_info;
	}

	/**
	 * @param int $user_id
	 * @param array $total_card
	 */
	public static function updateTotalCard($user_id, $total_card)
	{
		$sql = 'UPDATE set_info SET total_card = ? WHERE id = (
					SELECT set_id FROM user_log WHERE user_id = ? && status = ? LIMIT 1
				) LIMIT 1';
		$bind = [ $total_card, $user_id, SLS_READY ];
		return DB::update($sql, $bind) === 1;
	}

	/**
	 * 抓牌请求，可能是一张，也可能是多张，根据当时牌局来
	 * @param int $set_id
	 * @param int $user_id
	 * @return null|array $card_list
	 */
	public static function get($set_id, $user_id)
	{
	}

	/**
	 * 用户操作
	 * @param int $set_id
	 * @param int $user_id
	 * @param int $cmd
	 * @param int $card_index_list
	 * @return bool
	 */
	public static function op($set_id, $user_id, $cmd, $card_index_list)
	{}
}
