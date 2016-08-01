<?php

define('MAHJONG_TYPE_SEQ',				's');
define('MAHJONG_TYPE_WORD',				'w');
define('MAHJONG_TYPE_FLOUR',				'f');

/**
 * For Test Echo
 */
function dump($p)
{
	if (is_array($p)) {
		foreach($p as $e) {
			echo
				//'[',$e['index'],']',
				//'[',$e['value'],']',
				'|',$e['name'],'|';
		}
	} elseif (is_string($p)) {
		echo $p;
	} else {
		var_dump($p);
	}
	echo "\n";
}

class Mahjong
{
	public static function dynamic_detail($uid)
	{
		return [
			'hand_cards' => [],// 手牌
			'cur_card' => null,// 当前牌（别人出的牌或者自己摸得牌）
			'hua_cards' => [],// 花牌
		];
	}

	/**
	 * 排序
	 * @param array $cards
	 * @return array
	 */
	public static function order($cards)
	{
		usort($cards, function($a, $b){
			return $a['value'] - $b['value'];
		});
		return $cards;
	}

	/**
	 * 乱序
	 * @param array $cards
	 * @return array
	 */
	public static function disorder($cards)
	{
		shuffle($cards);
		return $cards;
	}

	/**
	 * 判断是否能吃
	 * @param array $hand_cards
	 * @param array $get_card
	 * @return array | null
	 */
	public static function isChi($hand_cards, $get_card)
	{
		$cards = self::order($hand_cards);
		$stack = [];
		$ret = null;
		foreach ($cards as $card) {
			if ($card['used']) {
				continue;
			}
			if (count($stack) === 2) {
				array_shift($stack);
			}
			$stack[] = $card;
			if (count($stack) < 2) {
				continue;
			}
			if (self::isShun($stack[0], $stack[1], $get_card)) {
				$ret = [
					$stack[0],
					$stack[1],
				];
			}
		}
		return $ret;
	}

	/**
	 * 判断是否能碰
	 * @param array $hand_cards
	 * @param array $get_card
	 * @return array | null
	 */
	public static function isPeng($hand_cards, $get_card)
	{
		$cards = self::order($hand_cards);
		$stack = [];
		$ret = null;
		foreach ($cards as $card) {
			if ($card['used']) {
				continue;
			}
			if (count($stack) === 2) {
				array_shift($stack);
			}
			$stack[] = $card;
			if (count($stack) < 2) {
				continue;
			}
			if (self::isKe($stack[0], $stack[1], $get_card)) {
				$ret = [
					$stack[0],
					$stack[1],
				];
			}
		}
		return $ret;
	}

	/**
	 * 判断是否能杠
	 * @param array $hand_cards
	 * @param array $get_card
	 * @return array | null
	 */
	public static function isGang($hand_cards, $get_card)
	{
		$cards = self::order($hand_cards);
		$stack = [];
		$ret = null;
		foreach ($cards as $card) {
			if ($card['used']) {
				continue;
			}
			if (count($stack) === 3) {
				array_shift($stack);
			}
			$stack[] = $card;
			if (count($stack) < 3) {
				continue;
			}
			if (self::isGangPai($stack[0], $stack[1], $stack[2], $get_card)) {
				$ret = [
					$stack[0],
					$stack[1],
					$stack[2],
				];
			}
		}
		return $ret;
	}

	/**
	 * 判断是否能胡
	 * @param array $hand_cards
	 * @param array $get_card
	 * @return bool
	 */
	public static function isHu($hand_cards, $get_card)
	{
		$fan_list = self::calFan($hand_cards, $get_card);
		$tf = 0;
		foreach ($fan_list as $fan) {
			$tf += $fan['value'];
		}
		if ($fan >= 8) {
			return true;
		}
		return false;
	}

	/**
	 * 算番
	 * @param array $hand_cards
	 * @param array $get_card
	 * @param array $hua_cards
	 * @return array
	 */
	public static function calFan($hand_cards, $get_card)
	{
		$fan_map = [
			// 88番
			// 1、大四喜：由4副风刻（杠）组成的和牌。不计圈风刻、门风刻、三风刻、碰碰和、幺九刻。
			[
				'index' => 1,
				'name' => '大四喜',
				'value' => 88,
				'ignore' => [],
				'func' => function($hand_cards, $get_card){
					$cur_cards = array_merge($hand_cards, [ $get_card ]);
					$count_map = [];
					foreach ($cur_cards as $card) {
						$key = $card['value'];
						if (!array_key_exists($key, $count_map)) {
							$count_map[$key] = [];
						}
						$count_map[$key][] = $card;
					};
					for ($i = 31; $i < 35; $i++) {
						if (!array_key_exists($i ,$count_map) || count($count_map[$i]) < 3) {
							return false;
						}
						if (count($count_map[$i]) == 4) { // 检验是杠的情况
							foreach($count_map[$i] as $card) {
								if (count($card['combine']) != 4) {
									return false;
								}
							}
						}
					}
					
					foreach ($count_map as $i => $e) {
						if ($i < 31 || $i > 35) {
							if (count($e) != 2) {
								return false;
							}
						}
					}
					return true;
				},
			],
			// 2、大三元：和牌中，有中发白3副刻子。不计双箭刻、箭刻。
			[
				'index' => 2,
				'name' => '大三元',
				'value' => 88,
				'ignore' => [],
				'func' => function($hand_cards, $get_card){
					$cur_cards = array_merge($hand_cards, [ $get_card ]);
					$count_map = [];
					foreach ($cur_cards as $card) {
						$key = $card['value'];
						if (!array_key_exists($key, $count_map)) {
							$count_map[$key] = [];
						}
						$count_map[$key][] = $card;
					};
					for ($i = 35; $i < 38; $i++) {
						if (!array_key_exists($i ,$count_map) || count($count_map[$i]) < 3) {
							return false;
						}
						if (count($count_map[$i]) == 4) { // 检验是杠的情况
							foreach($count_map[$i] as $card) {
								if (count($card['combine']) != 4) {
									return false;
								}
							}
						}
					}
					// TODO 剩下的牌能胡
					return true;
				}
			],
			// 3、绿一色：由23468条及发字中的任何牌组成的和牌。按新规定，无“发”时可计清一色、断幺，有“发”时可计混一色。
			[
				'index' => 3,
				'name' => '绿一色',
				'value' => 88,
				'ignore' => [],
				'func' => function($hand_cards, $get_card){
					return false;
				}
			],
			// 4、九莲宝灯：由一种花色序数牌按①①①②③④⑤⑥⑦⑧⑨⑨⑨组成的特定牌型，见同花色任何1张序数牌即成和牌。不计清一色、门前清、幺九刻，自摸计不求人。因听牌时听同花色所有9种牌而得名。如不是听九种牌的情况但和牌后牌型符合九莲宝灯牌型的，一般不算九莲宝灯，但有的场合也算(如QQ麻将)。
			[
				'index' => 4,
				'name' => '九莲宝灯',
				'value' => 88,
				'ignore' => [],
				'func' => function($hand_cards, $get_card){
					return true;
				}
			],
			// 5、十八罗汉：4个杠，因和牌时总共18张牌，又称“十八罗汉”。
			[
				'index' => 5,
				'name' => '十八罗汉',
				'value' => 88,
				'ignore' => [],
				'func' => function($hand_cards, $get_card){
					return false;
				}
			],
			// 6、连七对：由一种花色序数牌组成序数相连的7个对子的和牌。不计七对、清一色、不求人、单钓。
			[
				'index' => 6,
				'name' => '连七对',
				'value' => 88,
				'ignore' => [],
				'func' => function($hand_cards, $get_card){
					return false;
				}
			],
			// 7、十三幺：由3种序数牌的一、九牌，7种字牌及其中一对作将组成的和牌。不计五门齐、门前清、单钓，自摸加计不求人。又称“国士无双”。
			[
				'index' => 7,
				'name' => '十三幺',
				'value' => 88,
				'ignore' => [],
				'func' => function($hand_cards, $get_card){
					return false;
				}
			],
			// 64番
			// 8、清幺九：由序数牌一、九组成的和牌。不计碰碰和、全带幺、幺九刻、无字。可计七对、两组双同刻或一组三同刻。又称“清老头”。
			// 9、小四喜：和牌时有风牌的3副刻子及将牌。不计三风刻。
			// 10、小三元：和牌时有箭牌的两副刻子和将牌。不计箭刻。
			// 11、字一色：由字牌组成的和牌。不计碰碰和，可计七对。
			// 12、四暗刻：4个暗刻（暗杠）。不计门前清、碰碰和。
			// 13、一色双龙会：一种花色的两个老少副，5为将牌。不计平和、七对、清一色。
			// 48番
			// 14、一色四同顺：一种花色4副序数相同的顺子，不计三杠、一色三节高、一般高、四归一。
			// 15、一色四节高：一种花色4副依次递增一位数的刻子，不计一色三同顺、碰碰和。
			// 32番
			// 16、一色四步高：一种花色4副依次递增一位数或依次递增二位数的顺子。
			// 17、三杠：整副牌中有3个杠子。
			// 18、混幺九：由字牌和序数牌一、九组成的和牌。不计碰碰和。可计七对。又称“混老头”。
			// 24番
			// 19、七对：由7个对子组成的和牌。不计门前清、单钓，自摸计不求人。又称“七对子”“七小对”等。
			// 20、七星不靠：必须有7个单张的东西南北中发白，加上3种花色，数位按147、258、369中的7张序数牌组成没有将牌的和牌。不计五门齐、门前清、单钓。
			// 21、全双刻：由2、4、6、8序数牌的刻子、将牌组成的和牌。不计碰碰和、断幺。
			// 22、清一色：由一种花色的序数牌组成的和牌。不计无字。
			// 23、一色三同顺：和牌时有一种花色3副序数相同的顺子。不计一色三节高。
			// 24、一色三节高：和牌时有一种花色3副依次递增一位数字的刻子。不计一色三同顺。
			// 25、全大：由序数牌789组成的顺子、刻子（杠）、将牌的和牌。不计无字。
			// 26、全中：由序数牌456组成的顺子、刻子（杠）、将牌的和牌。不计断幺。
			// 27、全小：由序数牌123组成的顺子、刻子（杠）、将牌的和牌。不计无字。
			// 16番
			// 28、清龙：和牌时，有一种花1-9相连接的序数牌。又称“一条龙”“一气通贯”等。
			// 29、三色双龙会：2种花色2个老少副、另一种花色5作将的和牌。不计喜相逢、老少副、无字、平和。
			// 30、一色三步高：和牌时，有一种花色3副依次递增一位或依次递增二位数字的顺子。
			// 31、全带五：每副牌及将牌必须有5的序数牌。不计断幺。
			// 32、三同刻：3个序数相同的刻子（杠）。
			// 33、三暗刻：3个暗刻，如果满足“碰碰和”牌型，可同时计算。
			// 12番
			// 34、全不靠：由单张3种花色147、258、369不能错位的序数牌及东南西北中发白中的任何14张牌组成的和牌。不计五门齐、门前清、单钓。若和牌时147 258 369都有，则加计组合龙。
			// 35、组合龙：3种花色的147、258、369不能错位的序数牌。若和牌时另两组牌为顺子+将，可计平和。
			// 36、大于五：由序数牌6-9的顺子、刻子、将牌组成的和牌。不计无字。
			// 37、小于五：由序数牌1-4的顺子、刻子、将牌组成的和牌。不计无字。
			// 38、三风刻：3个风刻。
			// 8番
			// 39、花龙：3种花色的3副顺子连接成1-9的序数牌。
			// 40、推不倒：由牌面图形没有上下区别的牌组成的和牌，包括1234589饼、245689条、白板。不计缺一门。
			// 41、三色三同顺：和牌时，有3种花色3副序数相同的顺子。
			// 42、三色三节高：和牌时，有3种花色3副依次递增一位数的刻子。
			// 43、无番和：和牌后，数不出任何番种分（花牌不计算在内）。
			// 44、妙手回春：自摸牌墙上最后一张牌和牌。不计自摸。
			// 45、海底捞月：和打出的最后一张牌。
			// 46、杠上开花：开杠抓进的牌成和牌（不包括补花）。不计自摸。
			// 47、抢杠和：和别人自抓开明杠的牌。不计和绝张。
			// 6番
			// 48、碰碰和：由4副刻子（或杠）、将牌组成的和牌。又称“对对和(胡)”
			// 49、混一色：由一种花色序数牌及字牌组成的和牌。
			// 50、三色三步高：3种花色3副依次递增一位序数的顺子。
			// 51、五门齐：和牌时3种序数牌、风、箭牌齐全。
			// 52、全求人：全靠吃牌、碰牌、单钓别人打出的牌和牌。不计单钓。
			// 53、双暗杠：2个暗杠。
			// 54、双箭刻：2副箭刻（或杠）。
			// 4番
			// 55、全带幺：和牌时，每副牌、将牌都有幺牌。
			// 56、不求人：4副牌及将中没有吃牌、碰牌（包括明杠），所有的牌包括所和的牌全部是自己摸到的。又称“门清自摸”。
			// 57、双明杠：2个明杠。
			// 58、和绝张：和牌池、桌面已亮明的3张牌所剩的第4张牌（抢杠和不计和绝张）。
			// 2番
			// 59、箭刻：由中、发、白3张相同的牌组成的刻子。
			// 60、圈风刻：与圈风相同的风刻。
			// 61、门风刻：与本门风相同的风刻。
			// 62、门前清：没有吃、碰、明杠而听牌，和别人打出的牌。又称“门清”。
			// 63、平和：由4副顺子及序数牌作将组成的和牌，边、坎、钓不影响平和。
			// 64、四归一：和牌中，有4张相同的牌归于一家的顺子、刻子、对子、将牌中（不包括杠牌）。
			// 65、双同刻：2副序数相同的刻子。
			// 66、双暗刻：2个暗刻。
			// 67、暗杠：自抓4张相同的牌开杠。
			// 68、断幺：和牌中没有一、九及字牌。
			// 1番
			// 69、一般高：由一种花色2副相同的顺子组成的牌。又称“一色二顺”“一杯口”。
			// 70、喜相逢：2种花色2副序数相同的顺子。
			// 71、连六：一种花色6张相连接的序数牌。
			// 72、老少副：一种花色牌的123、789两副顺子。
			// 73、幺九刻：3张相同的一、九序数牌及字牌组成的刻子（或杠）。
			// 74、明杠：自己有暗刻，碰别人打出的一张相同的牌开杠；或自己抓进一张与碰的明刻相同的牌开杠。
			// 75、缺一门：和牌中缺少一种花色序数牌。
			// 76、无字：和牌中没有风、箭牌。
			// 77、边张：单和123的3及789的7或1233和3、7789和7都为边张。手中有12345和3，56789和7不算和边张。
			// 78、坎张：和2张牌之间的牌。4556和5也为坎张，手中有45567和6不算坎张。
			// 79、单钓将：钓单张牌作将成和。
			// 80、自摸：自己抓进牌成和牌。
		];
		// 外部调用之后，注意处理花牌的番
		// 81、花牌：即春夏秋冬，梅兰竹菊，每花计一分。不计在起和分内，和牌后才能计分。花牌补花成和计自摸分，不计杠上开花。
		$ret = [];
		$ignore_list = [];
		foreach ($fan_map as $fan) {
			if ($fan['func']($hand_cards,$get_card)) {
				if (in_array($fan['index'], $ignore_list)) {
					continue;
				}
				$ignore_list = array_merge($ignore_list, $fan['ignore']);
				$ret[] = $fan;
			}
		}
		return $ret;
	}

	/**
	 * 判断是否是顺
	 * @param array $card1
	 * @param array $card2
	 * @param array $card3
	 * @return bool
	 */
	private static function isShun($card1, $card2, $card3)
	{
		$stack = [ $card1, $card2, $card3 ];
		$stack = self::order($stack);
		return $stack[2]['value'] < 30
			&& ($stack[1]['value'] - $stack[0]['value']) == 1
			&& ($stack[2]['value'] - $stack[1]['value']) == 1;
	}

	/**
	 * 判断是否是刻
	 * @param array $card1
	 * @param array $card2
	 * @param array $card3
	 * @return bool
	 */
	private static function isKe($card1, $card2, $card3)
	{
		return $card1['value'] == $card2['value'] && $card1['value'] == $card3['value'];
	}

	/**
	 * 判断是否是杠
	 * @param array $card1
	 * @param array $card2
	 * @param array $card3
	 * @param array $card4
	 * @return bool
	 */
	private static function isGangPai($card1, $card2, $card3, $card4)
	{
		return $card1['value'] === $card2['value']
			&& $card1['value'] === $card3['value']
			&& $card1['value'] === $card4['value'];
	}

	/**
	 * 判断是否是对子
	 * @param array $card1
	 * @param array $card2
	 * @return bool
	 */
	private static function isDui($card1, $card2)
	{
		return $card1['value'] === $card2['value'];
	}

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
	 */
	public static function genCard($index)
	{
		if ($index % 10 === 0) {
			return null;
		}
		$seq_name = ['一','二','三','四','五','六','七','八','九'];
		$seq_unit = ['萬','眼','条'];
		$word_name = ['东风','南风','西风','北风','红中','發财','白板'];
		$flour_name = ['春','夏','秋','冬','梅','兰','竹','菊'];
		$card_name = '未知';
		$card_type = '-';
		$card_value = floor($index / 40) * 10 + $index % 10;
		if ($index < 120) {
			$card_name = $seq_name[$index % 10 - 1] . $seq_unit[floor($index / 40)];
			$card_type = MAHJONG_TYPE_SEQ;
		} elseif ($index < 160) {
			if ($index % 10 > 7) {
				return null;
			}
			$card_name = $word_name[$index % 10 - 1];
			$card_type = MAHJONG_TYPE_WORD;
		} elseif ($index < 170) {
			if ($index % 10 > 8) {
				return null;
			}
			$card_name = $flour_name[$index % 10 - 1];
			$card_type = MAHJONG_TYPE_FLOUR;
			$card_value = 0;
		} else {
			return null;
		}
		return [
			'name' => $card_name,
			'type' => $card_type,
			'index' => $index,
			'value' => (int)$card_value,
			'used' => false,//用于表示已经吃碰杠过的牌
			'combine' => [],//用于记录与之组合的牌
		];
	}
}
