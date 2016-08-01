<?php

//mb_internal_encoding('UTF-8');

ignore_user_abort(true);

require dirname(__FILE__) . '/status.php';
require dirname(__FILE__) . '/class_loader.php';
require dirname(__FILE__) . '/base.php';

ClassLoader::init();
