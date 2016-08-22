<?php

require __DIR__ . '/../core/base.php';

$req = Base::getRequestJson();

$user_id = $req['user_id'];
$username = $req['username'];
$password = $req['password'];

if (!isset($username) && !isset($user_id)) {
	Base::dieWithError(ERROR_NOT_LOGIN);
}


if (!isset($user_id)) {
	if (!isset($username) || !isset($password)) {
		Base::dieWithError(ERROR_INVALID_REQUEST);
	}
	$user_id = User::login($username, $password);
	if (!isset($user_id)) {
		Base::dieWithError(ERROR_INVALID_REQUEST);
	}
	Base::login($user_id);
}

Base::dieWithResponse();
