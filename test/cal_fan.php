<?php

require __DIR__ . '/../core/base.php';

// 1、大四喜：由4副风刻（杠）组成的和牌。不计圈风刻、门风刻、三风刻、碰碰和、幺九刻。
$cards = [];
$cards[] = Mahjong::genCard(121);
$cards[] = Mahjong::genCard(122);
$cards[] = Mahjong::genCard(123);
$cards[] = Mahjong::genCard(124);

$cards[] = Mahjong::genCard(131);
$cards[] = Mahjong::genCard(132);
$cards[] = Mahjong::genCard(133);
$cards[] = Mahjong::genCard(134);

$cards[] = Mahjong::genCard(141);
$cards[] = Mahjong::genCard(142);
$cards[] = Mahjong::genCard(143);
$cards[] = Mahjong::genCard(144);

$cards[] = Mahjong::genCard(145);

$cards = Mahjong::order($cards);
$cur_card = Mahjong::genCard(155);
dump($cards);
dump([$cur_card]);
dump('################################');
dump(Mahjong::calFan($cards, $cur_card));
dump('================================');
/*
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
dump('################################');
dump(Mahjong::calFan($cards, $cur_card));
dump('================================');
*/
