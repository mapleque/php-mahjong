<?php

require __DIR__ . '/../core/base.php';

DB::exec('DELETE FROM game');
DB::exec('DELETE FROM user_log');
DB::exec('DELETE FROM set_info');
DB::exec('DELETE FROM user');
DB::exec("INSERT INTO user (username, password) VALUES('1','123')");

$user_id = DB::select("SELECT id FROM user WHERE username = '1' LIMIT 1")[0]['id'];
echo 'user ' . $user_id;
echo "\n";
$game_id = Game::create($user_id);
echo 'create game ';
dump($game_id);
echo "\n";
echo 'start game ';
dump(Game::start($user_id));
echo "\n";
$game_info = Game::getGameInfo($user_id);
echo 'game info ';
dump($game_info);
echo "\n";
echo 'game is ready ? ';
dump(Game::isReady($game_info));
echo "\n";
echo 'start set ';
dump(Set::start($game_id));
echo "\n";
echo 'set info ';
$set_info = Set::getSetInfo($user_id);
dump($set_info);
echo "\n";

// todo after set info start, there should be next setp with the game

