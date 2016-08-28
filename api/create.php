<?php

require __DIR__ . '/../core/base.php';

$req = Base::getRequestJson();

$user_id = $req['user_id'];
if (!isset($user_id)) {
	Base::dieWithError(ERROR_NOT_LOGIN);
}

$game_id = $req['game_id'];
if (!isset($game_id)) {
	$game_id = Game::create($user_id);
} else {
	if (!Game::attend($game_id, $user_id)) {
		Base::dieWithError(ERROR_INTERNAL);
	}
}

Base::dieWithResponse([ 'game_id' => $game_id ]);
