<?php

// mahjong.type
define('MAHJONG_TYPE_SEQ',				's'); // 序数牌 1-9*4*3
define('MAHJONG_TYPE_WORD',				'w'); // 字牌 7*4
define('MAHJONG_TYPE_FLOUR',			'f'); // 花牌 8

define('MENFENG_EAST',						1); // 门风
define('MENFENG_NORTH',						2); // 门风
define('MENFENG_WEST',						3); // 门风
define('MENFENG_SOUTH',						4); // 门风

// set.op
define('SET_OP_CHI',	'c'); // 吃
define('SET_OP_PENG',	'p'); // 碰
define('SET_OP_GANG',	'g'); // 杠
define('SET_OP_HU',		'h'); // 胡
define('SET_OP_PUSH',	'u'); // 出牌

// set_log.status
define('SLS_CREATE',			'c'); // 开始前
define('SLS_READY',				'r'); // 进行中
define('SLS_FINISH',			'f'); // 已结束
