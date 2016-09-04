<?php

DB::exec('CREATE TABLE game (
	id			INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	member		INT UNSIGNED NOT NULL,		# 本局人数设定
	set_seq		INT UNSIGNED NOT NULL,		# SEQ_* 本局门风
	game_seq	INT UNSIGNED NOT NULL,		# SEQ_* 本局圈风
	time		DATETIME NOT NULL
)');
