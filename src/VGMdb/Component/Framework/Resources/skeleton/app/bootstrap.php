<?php

/**
 * Bootstrap file for instantiating the autoloader.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */

return call_user_func(function (array $options) {
    if ($options['debug'] && !file_exists($options['base_dir'] . '/vendor/autoload.php')) {
        echo '<h1>Hello world! Let\'s set up your environment.</h1><ul><li><pre>cd ' . $options['base_dir'] . '</pre></li><li><pre>composer update</pre></li></ul>';
        echo '<h1>Checking requirements...</h1><pre>';
        require(__DIR__ . '/check.php');
        echo '</pre>';
        die();
    }

    $autoloader = require($options['base_dir'] . '/vendor/autoload.php');

    if ($options['debug'] && class_exists('VGMdb\\Component\\Composer\\Debug\\TraceableClassLoader')) {
        VGMdb\Component\Composer\Debug\TraceableClassLoader::register();
    }

    return $autoloader;
}, array(
    'debug' => isset($debug) ? $debug : (Boolean) getenv('HOST_DEBUG'),
    'cli' => ('cli' === PHP_SAPI),
    'base_dir' => dirname(__DIR__),
    'name' => isset($app_name) ? $app_name : (getenv('HOST_APP') ?: 'vgmdb')
));
