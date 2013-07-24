<?php

/**
 * Front end controller for PHPUnit testing in the browser.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */

$autoloader = require(dirname(__DIR__) . '/app/bootstrap.php');

$app = new PHPUnit_Html(array(
    'template' => 'bootstrap',
    'configuration' => dirname(__DIR__) . '/tests/phpunit.xml.dist',
    'coverageClover' => null,
    'reportDirectory' => (is_dir(dirname(__DIR__) . '/tests/reports') ? dirname(__DIR__) . '/tests/reports' : null)
));
$app->run();
