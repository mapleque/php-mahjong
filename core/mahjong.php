<?php

interface iMahjong
{
	/**
	 * 洗牌生成新的牌堆
	 * @return array [index]
	 */
	public static function getTotalCard();
	/**
	 * 从牌堆中获取初始牌
	 * @param array &$total_card
	 * @param bool $zhuang
	 * @return array [card]
	 */
	public static function getInitCard(&$total_card, $zhuang = false);
	/**
	 * 从牌堆中获取一张牌
	 * @param array &$total_card
	 * @param bool $desc
	 * @return array [card]
	 */
	public static function getOneCard(&$total_card);
	/**
	 * 查自己的牌有没有需要下一步的举动
	 * @return array [ op,... ]
	 */
	public static function checkBySelf($hand_cards, $new_card);
	/**
	 * 查别人出的牌有没有需要下一步的举动
	 * @return array [ op,... ]
	 */
	public static function checkByOther($hand_cards, $new_card);

	/**
	 * 排序
	 * @param array $cards
	 * @return array
	 */
	public static function order($cards);
	/**
	 * 乱序
	 * @param array $cards
	 * @return array
	 */
	public static function disorder($cards);
	/**
	 * 判断是否能吃
	 * @param array $hand_cards
	 * @param array $get_card
	 * @return array | null
	 */
	public static function isChi($hand_cards, $get_card);
	/**
	 * 判断是否能碰
	 * @param array $hand_cards
	 * @param array $get_card
	 * @return array | null
	 */
	public static function isPeng($hand_cards, $get_card);
	/**
	 * 判断是否能杠
	 * @param array $hand_cards
	 * @param array $get_card
	 * @return array | null
	 */
	public static function isGang($hand_cards, $get_card);
	/**
	 * 判断是否能胡
	 * @param array $hand_cards
	 * @param array $get_card
	 * @return bool
	 */
	public static function isHu($hand_cards, $get_card);
	/**
	 * 算番
	 * @param array $hand_cards
	 * @param array $get_card
	 * @param array $hua_cards
	 * @return array
	 */
	public static function calFan($hand_cards, $get_card);
	/**
	 * 根据序号生成这张麻将牌
	 * 国标麻将共144张
	 * index定义：
	 * 1-9,11-19,21-29,31-39为萬牌
	 * 41-49,51-59,61-69,71-79为眼牌
	 * 81-89,91-99，101-109,111-119为条牌
	 * 121-127,131-137,141-147,151-157为字牌（东南西北中发白）
	 * 161-168为花牌（初夏秋冬，梅兰竹菊）
	 * 再有其他规则，200以后补加
	 * value定义：
	 * 1-9萬牌
	 * 11-19眼牌
	 * 21-29条牌
	 * 31-37字牌
	 * 41-48花牌
	 * @param int $index
	 * @return array | null
	 *	 [
	 *	 	'name' => $card_name,
	 *	 	'type' => $card_type,
	 *	 	'index' => $index,
	 *	 	'value' => (int)$card_value,
	 *	 	'used' => false,//用于表示已经吃碰杠过的牌
	 *	 	'combine' => [],//用于记录与之组合的牌
	 *	 ];
	 */
	public static function genCard($index);
}

class Mahjong
{
	public static function getRule($rule)
	{
		return new $rule();
	}
}
