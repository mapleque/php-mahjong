<?php

require __DIR__ . '/../core/base.php';

$req = Base::getRequestJson();

$user_id = $req['user_id']);
if (!isset($user_id) {
	Base::dieWithError(ERROR_NOT_LOGIN);
}

$set_id = $req['set_id'];
if (!isset($set_id)) {
	Base::dieWithError(ERROR_INVALID_REQUEST);
}

Base::dieWithResponse(Set::getSetInfo($set_id, $user_id));
