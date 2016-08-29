<?php
require __DIR__ . '/../core/base.php';

DB::exec('CREATE TABLE set_info (
	id			INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	total_card	TEXT,
	cur_seq		INT UNSIGNED NOT NULL,
	time		DATETIME NOT NULL
)');

