<?php

DB::exec('CREATE TABLE set_info (
	id			INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	total_card	TEXT,							# 本局牌堆
	cur_seq		INT UNSIGNED NOT NULL,			# SEQ_* 当前操作门风
	wait_seqs	TEXT,							# SEQ_* 当前操作门风
	wait_card	TEXT,							# 当前需要判定的牌
	win_info	TEXT,							# 本盘结算信息
	time		DATETIME NOT NULL
)');

