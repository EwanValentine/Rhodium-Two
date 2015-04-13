<?php

ini_set('display_errors', 1);
error_reporting(-1);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/bootstrap.php';

new Rhodium\AppKernel();
