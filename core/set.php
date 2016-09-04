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
		$sql = 'INSERT INTO set_info (total_card, cur_seq, time) SELECT ?, set_seq, NOW() FROM game WHERE id = ? LIMIT 1';
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
		// TODO fix select
		$sql = 'SELECT set_id, game_id, win_info, cur_seq, wait_seqs, wait_card
				FROM user_log
				INNER JOIN set_info ON set_id = set_info.id
				WHERE user_id = ? && status = ? LIMIT 1';
		$set_info = DB::select($sql, [ $user_id, SLS_READY ])[0];
		$set_info['wait_card'] = json_decode($set_info['wait_card'], true);
		$set_info['wait_seqs'] = $set_info['wait_seqs'] ?
			explode(',', $set_info['wait_seqs']) : [];
		$sql = 'SELECT status, seq, ops, hand, pool FROM user_log WHERE game_id = ? && status = ?';
		$log_info = DB::select($sql, [ $set_info['game_id'], SLS_READY ]);
		foreach ($log_info as &$info) {
			$info['hand'] = json_decode($info['hand'], true);
			$info['pool'] = json_decode($info['pool'], true);
		}
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
	 * 用户操作
	 * @param int $set_id
	 * @param int $user_id
	 * @param int $cmd
	 * @param int $card_index_list
	 * @return bool
	 */
	public static function op($user_id, $cmd, $card_index_list = [])
	{
		$rule = Mahjong::getRule('Standard');
		$sql = 'SELECT user_log.id AS log_id, set_info.id AS set_id,
					member, total_card, hand, pool,
					game.set_seq AS game_seq, user_log.seq AS user_seq,
					set_info.cur_seq, wait_seqs, wait_card
				FROM user_log
				INNER JOIN set_info ON set_info.id = set_id
				INNER JOIN game ON game.id = game_id
				WHERE user_id = ? && status = ? && FIND_IN_SET(?,ops)
						&& (cur_seq = user_log.seq
							|| FIND_IN_SET(user_log.seq, wait_seqs))
				LIMIT 1';
		$bind = [ $user_id, SLS_READY, $cmd ];
		$info = DB::select($sql, $bind)[0];
		if (!isset($info)) {
			return false;
		}
		$log_id = $info['log_id'];
		$set_id = $info['set_id'];

		DB::begin();
		switch ($cmd) {
			case OP_CHI:
				break;
			case OP_PENG:
				break;
			case OP_GANG:
				break;
			case OP_HU:
				break;
			case OP_PUSH:
				// TODO 出牌后需要轮询，放弃后还要回本家
				$hand = json_decode($info['hand'], true);
				$card = array_splice($hand, $card_index_list[0], 1)[0];
				$wait_card = json_decode($info['wait_card'], true);
				if (isset($wait_card)) {
					// 初始化抓完牌的时候并没有wait_card
					// chi或者peng之后也没有wait_card
					$hand[] = $wait_card;
				}
				$hand = $rule->order($hand);

				$sql = 'SELECT id, hand, seq FROM user_log
						WHERE set_id = ? && user_id != ? ORDER BY seq';
				$other_users = DB::select($sql, [ $set_id, $user_id ]);
				$wait_seqs = [];
				foreach ($other_users as $user) {
					$user_hand = json_decode($user['hand'], true);
					$op = $rule->checkByOther($hand, $card);
					if (isset($op)) {
						$sql = 'UPDATE user_log SET op = ? WHERE id = ? LIMIT 1';
						$bind = [ implode(',', $op), $user['id'] ];
						if (DB::update($sql, $bind) !== 1) {
							DB::commit(false);
							return false;
						}
					}
				}
				$sql = 'UPDATE set_info
						SET wait_seqs = ?, wait_card = ?, cur_seq = ?
						WHERE id = ? LIMIT 1';
				$bind = [ empty($wait_seqs)? null :implode(',', $wait_seqs),
					json_encode($card),
					($info['cur_seq'] + 1) % $info['member'], $set_id ];
				if (DB::update($sql, $bind) !== 1) {
					DB::commit(false);
					return false;
				}

				$sql = 'UPDATE user_log SET hand = ?, ops = ?
						WHERE id = ? LIMIT 1';
				$bind = [ json_encode($hand), OP_GET, $log_id ];
				if (DB::update($sql, $bind) !== 1) {
					DB::commit(false);
					return false;
				}
				break;
			case OP_GET:
				$total_card = json_decode($info['total_card'], true);
				$card = $rule->getOneCard($total_card);
				$hand = json_decode($info['hand'], true);
				$op = $rule->checkBySelf($hand, $card);
				if (!empty($op)) {
					$next_cmd = implode(',', $op);
				} else {
					$next_cmd = OP_PUSH;
				}
				$sql = 'UPDATE user_log SET ops = ?
						WHERE id = ? LIMIT 1';
				$bind = [ $next_cmd, $log_id ];
				if (DB::update($sql, $bind) !== 1) {
					DB::commit(false);
					return false;
				}
				$sql = 'UPDATE set_info
						SET wait_card = ?
						WHERE id = ? LIMIT 1';
				$bind = [ json_encode($card), $set_id ];
				if (DB::update($sql, $bind) !== 1) {
					DB::commit(false);
					return false;
				}
				break;
			case OP_INIT:
				$total_card = json_decode($info['total_card'], true);
				$next_cmd = OP_GET;
				$hand = $rule->getInitCard($total_card);
				$sql = 'UPDATE user_log SET ops = ?, hand = ?
						WHERE id = ? LIMIT 1';
				$bind = [ $next_cmd, json_encode($hand), $log_id ];
				if (DB::update($sql, $bind) !== 1) {
					DB::commit(false);
					return false;
				}
				$sql = 'UPDATE set_info SET cur_seq = ?, total_card = ?
						WHERE id = ? LIMIT 1';
				$bind = [ ($info['set_seq'] + 1) % $info['member'],
					json_encode($total_card), $set_id ];
				if (DB::update($sql, $bind) !== 1) {
					DB::commit(false);
					return false;
				}
				break;
			case PASS:
				$wait_seqs = explode(',', $info['wait_seqs']);
				array_shift($wait_seqs);
				$sql = 'UPDATE set_info SET wait_seqs = ?
						WHERE id= ? LIMIT 1';
				$bind = [ implode(',', $wait_seqs), $set_id ];
				if (DB::update($sql, $bind) !== 1) {
					DB::commit(false);
					return false;
				}
				$sql = 'UPDATE user_log SET ops = ? WHERE id = ? LIMIT 1';
				$bind = [ OP_GET, $log_id ];
				break;
			default:
				DB::commit(false);
				return false;
		}
		DB::commit(true);
		return true;
	}
}
