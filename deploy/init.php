<?php

require __DIR__ . '/../core/base.php';

DB::exec('DROP TABLE game');
DB::exec('DROP TABLE user_log');
DB::exec('DROP TABLE set_info');
DB::exec('DROP TABLE user');
include __DIR__ . '/../deploy/user.php';
include __DIR__ . '/../deploy/game.php';
include __DIR__ . '/../deploy/user_log.php';
include __DIR__ . '/../deploy/set_info.php';

