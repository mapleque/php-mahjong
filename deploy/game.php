<?php
require __DIR__ . '/../core/base.php';

DB::exec('CREATE TABLE game (
	id			INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	member		INT UNSIGNED NOT NULL,
	time		DATETIME NOT NULL
)');
