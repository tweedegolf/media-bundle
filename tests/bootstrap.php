<?php

if (!is_file($autoload = __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install"?');
}

require $autoload;
