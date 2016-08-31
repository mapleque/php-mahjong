<?php

require dirname(__FILE__) . '/../common/include.php';

ClassLoader::appendMap([
	'User'			=> 'user',
	'Mahjong'		=> 'mahjong',
	'Standard'		=> 'standard',
	'Game'		=> 'game',
	'Set'		=> 'set',
]);
