<?php

DB::exec('CREATE TABLE user_log (
	id			INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	user_id		INT UNSIGNED,
	game_id		INT UNSIGNED,
	set_id		INT UNSIGNED,
	status		CHAR(1) NOT NULL,			# SLS_* 当前游戏状态
	seq			INT UNSIGNED NOT NULL,		# SEQ_* 当前玩家的门风
	ops			TEXT NOT NULL,				# OP_*,... 玩家下一步将要进行的操作
	hand		TEXT,						# 玩家手牌
	pool		TEXT,						# 玩家弃牌
	time		DATETIME NOT NULL
)');

DB::exec('ALTER TABLE user_log ADD INDEX (user_id), ADD INDEX (game_id, set_id)');
