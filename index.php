<?php

require 'vendor/autoload.php';

$app = new Kaliba\Foundation\Application(realpath(__DIR__) );

$kernel = $app->make(Kaliba\Routing\Kernel::class);

$kernel->handle(Kaliba\Http\Request::instance());


