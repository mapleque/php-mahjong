<?php

require dirname(__FILE__) . '/../common/include.php';
require dirname(__FILE__) . '/enum.php';
require dirname(__FILE__) . '/status.php';

ClassLoader::appendMap([
	'User'			=> 'user',
	'Mahjong'		=> 'mahjong',
	'Standard'		=> 'standard',
	'Game'		=> 'game',
	'Set'		=> 'set',
]);
