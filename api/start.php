<?php

require __DIR__ . '/../core/base.php';

$req = Base::getRequestJson();

$user_id = $req['user_id'];
if (!isset($user_id)) {
	Base::dieWithError(ERROR_NOT_LOGIN);
}


$game_id = $req['game_id'];
if (!isset($game_id)) {
	Base::dieWithError(ERROR_INVALID_REQUEST);
}

if (!Game::start($user_id)) {
	Base::dieWithError(ERROR_INTERNAL);
}

$game_info = Game::getGameInfo($user_id);
if (!Game::isReady($game_info)) {
	Base::dieWithResponse();
}

if (!Set::start($game_id)) {
	Base::dieWithError(ERROR_INTERNAL);
}

Base::dieWithResponse();
