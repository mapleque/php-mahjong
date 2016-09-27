<?php

// mahjong.type
define('MAHJONG_TYPE_SEQ',				's'); // 序数牌 1-9*4*3
define('MAHJONG_TYPE_WORD',				'w'); // 字牌 7*4
define('MAHJONG_TYPE_FLOUR',			'f'); // 花牌 8

define('SEQ_EAST',						0); // 风
define('SEQ_NORTH',						1); // 风
define('SEQ_WEST',						2); // 风
define('SEQ_SOUTH',						3); // 风

// set.op
define('OP_CHI',	'c'); // 吃
define('OP_PENG',	'p'); // 碰
define('OP_GANG',	'g'); // 杠
define('OP_HU',		'h'); // 胡
define('OP_PUSH',	'u'); // 出牌
define('OP_GET',	't'); // 抓牌
define('OP_INIT',	'i'); // 开始抓牌
define('OP_PASS',	's'); // 放弃本轮操作

// user_log.status
define('SLS_CREATE',			'c'); // 开始前
define('SLS_READY',				'r'); // 进行中
define('SLS_FINISH',			'f'); // 已结束
