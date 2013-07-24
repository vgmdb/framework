<?php

/**
 * VGMdb is a crowdsourced music and soundtrack database for games and visual arts.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 * @author Secret Squirrel <secretsquirrel@vgmdb.net>
 */

$debug = getenv('HOST_DEBUG') ? true : false;
$env   = getenv('HOST_ENV') ?: 'prod';

$autoloader = require(dirname(__DIR__) . '/app/bootstrap.php');

VGMdb\Component\HttpKernel\Debug\ExceptionHandler::register($debug);
VGMdb\Component\HttpKernel\Debug\ErrorHandler::register($debug);

$app = require(dirname(__DIR__) . '/app/app.php');
$app->run(VGMdb\Component\HttpFoundation\Request::createFromGlobals());
