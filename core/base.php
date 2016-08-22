<?php

require dirname(__FILE__) . '/../common/include.php';

ClassLoader::appendMap([
	'User'			=> 'user',
	'Mahjong'		=> 'mahjong',
	'Game'		=> 'game',
	'Set'		=> 'set',
]);
