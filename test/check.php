<?php

require __DIR__ . '/../core/base.php';

$cards = [];
for ($i = 1; $i < 10; $i++) {
	$cards[] = Mahjong::genCard($i);
}
$cards[] = Mahjong::genCard(11);
$cards[] = Mahjong::genCard(21);
$cards[] = Mahjong::genCard(19);
$cards[] = Mahjong::genCard(29);

$cards = Mahjong::order($cards);
$cur_card = Mahjong::genCard(31);
dump($cards);
dump([$cur_card]);

dump('all should be true');
echo 'chi:';
dump(Mahjong::isChi($cards, $cur_card));
echo 'peng:';
dump(Mahjong::isPeng($cards, $cur_card));
echo 'gang:';
dump(Mahjong::isGang($cards, $cur_card));
echo 'ting:';
dump(Mahjong::isTing($cards, $cur_card));
echo 'hu:';
dump(Mahjong::isHu($cards, $cur_card));

echo "\n";
echo "\n";

$cur_card = Mahjong::genCard(51);
dump($cards);
dump([$cur_card]);

dump('all should be false');
echo 'chi:';
dump(Mahjong::isChi($cards, $cur_card));
echo 'peng:';
dump(Mahjong::isPeng($cards, $cur_card));
echo 'gang:';
dump(Mahjong::isGang($cards, $cur_card));
echo 'ting:';
dump(Mahjong::isTing($cards, $cur_card));
echo 'hu:';
dump(Mahjong::isHu($cards, $cur_card));
