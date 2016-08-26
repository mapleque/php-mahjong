<?php

DB::exec('CREATE TABLE user_log (
	id			INT UNSIGNED AUTO INCREMENT PRIMARY KEY NOT NULL,
	user_id		INT UNSIGNED,
	game_id		INT UNSIGNED,
	set_id		INT UNSIGNED,
	status		CHAR(1) NOT NULL,
	hand		TEXT,
	pool		TEXT,
	waiting		TEXT,
	win_info	TEXT,
	time		DATETIME DEFAULT NOW() NOT NULL
)');

DB::exec('ALTER TABLE set_log ADD INDEX (user_id), ADD INDEX (game_id, set_id)');
