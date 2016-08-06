<?php

class Set
{
	/**
	 * 创建一盘
	 * @return int $set_id
	 */
	public static function create()
	{
		$set_id = 0;
		return $set_id;
	}

	/**
	 * 获取盘信息
	 * @param int $set_id
	 * @param int $user_id
	 * @return array
	 */
	public static function getSetInfo($set_id, $user_id)
	{
		
	}

	/**
	 * 抓牌请求，可能是一张，也可能是多张，根据当时牌局来
	 * @param int $set_id
	 * @param int $user_id
	 * @return null|array $card_list
	 */
	public static function get($set_id, $user_id)
	{}

	/**
	 * 用户操作
	 * @param int $set_id
	 * @param int $user_id
	 * @param int $cmd
	 * @param int $card_index_list
	 * @return bool
	 */
	public static function get($set_id, $user_id, $cmd, $card_index_list)
	{}

}