<?php

require __DIR__ . '/../core/base.php';

$req = Base::getRequestJson();

$user_id = $req['user_id']);
if (!isset($user_id) {
	Base::dieWithError(ERROR_NOT_LOGIN);
}

$cmd = $req['cmd'];
if (!isset($cmd)) {
	Base::dieWithError(ERROR_INVALID_REQUEST);
}

$card_index_list = $req['card_index_list'] ?: [];

if (!Set::op($user_id, $cmd, $card_index_list)) {
	Base::dieWithError(ERROR_INTERNAL);
}

Base::dieWithRessponse();
