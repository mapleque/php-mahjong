<?php

// mahjong.type
define('MAHJONG_TYPE_SEQ',				's'); // 序数牌 1-9*4*3
define('MAHJONG_TYPE_WORD',				'w'); // 字牌 7*4
define('MAHJONG_TYPE_FLOUR',			'f'); // 花牌 8

define('EAST',						1); // 风
define('NORTH',						2); // 风
define('WEST',						3); // 风
define('SOUTH',						4); // 风

// set.op
define('CHI',	'c'); // 吃
define('PENG',	'p'); // 碰
define('GANG',	'g'); // 杠
define('HU',		'h'); // 胡
define('PUSH',	'u'); // 出牌
define('GET',	't'); // 抓牌
define('GET4',	'4'); // 开始抓牌

// set_log.status
define('SLS_CREATE',			'c'); // 开始前
define('SLS_READY',				'r'); // 进行中
define('SLS_FINISH',			'f'); // 已结束
