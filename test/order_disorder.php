<?php

require __DIR__ . '/../core/base.php';

$cards = [];
for ($i = 1; $i < 200; $i++) {
	$card = Mahjong::genCard($i);
	if (isset($card)) {
		$cards[] = $card;
	}
}
foreach($cards as $card) {
	echo $card['index'],$card['name'],$card['value']," ";
}
echo "\n";
echo "\n";
$cards = Mahjong::disorder($cards);
foreach($cards as $card) {
	echo $card['index'],$card['name'],$card['value']," ";
}
echo "\n";
echo "\n";
$cards = Mahjong::order($cards);
foreach($cards as $card) {
	echo $card['index'],$card['name'],$card['value']," ";
}
echo "\n";
