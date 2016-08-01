<?php

require __DIR__ . '/../core/base.php';

//TODO: 参数检验

$ret = One::getOneCount();

Base::dieWithResponse([ 'count' => $ret ]);
