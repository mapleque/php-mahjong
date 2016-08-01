<?php

require __DIR__ . '/../core/base.php';

$req = Base::getRequestJson();

//TODO: 参数检验

$ret = One::getOne($req->key);

Base::dieWithResponse($ret);
