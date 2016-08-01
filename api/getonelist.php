<?php

require __DIR__ . '/../core/base.php';

$req = Base::getRequestJson();

//TODO: 参数检验

if (!isset($req->start)) {
	$ret = One::getOneList();
} else if (!isset($req->num)) {
	$ret = One::getOneList($req->start);
} else {
	$ret = One::getOneList($req->start, $req->num);
}

Base::dieWithResponse($ret);
