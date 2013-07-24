<?php

/**
 * The VGMdb Framework.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */

$app = call_user_func(function (array $options) {
    $class = $options['debug'] ? 'VGMdb\\TraceableApplication' : 'VGMdb\\Application';

    return new $class($options);
}, array(
    'debug' => isset($debug) ? $debug : (Boolean) getenv('HOST_DEBUG'),
    'cache' => isset($cache) ? $cache : true,
    'cli' => ('cli' === PHP_SAPI),
    'env' => isset($env) ? $env: (getenv('HOST_ENV') ?: 'prod'),
    'name' => isset($app_name) ? $app_name : (getenv('HOST_APP') ?: 'vgmdb'),
    'base_dir' => dirname(__DIR__),
    'cache_dir' => __DIR__ . '/cache',
    'log_dir' => __DIR__ . '/logs',
));

$app->register(new VGMdb\Component\Framework\FrameworkServiceProvider(), array(
    'framework.cache_dir' => $app['cache_dir'] . '/configs',
    'framework.cache_class' => $app['name'] . '-' . $app['env'] . 'Config',
    'framework.config_dirs' => array($app['base_dir'] . '/app/Resources/config'),
    'framework.config_file' => 'config.dist.yml',
    'framework.loader.passes' => array(
        'VGMdb\\Component\\Silex\\Loader\\Pass\\DatabasePass',
        'VGMdb\\Component\\Silex\\Loader\\Pass\\QueuePass',
        'VGMdb\\Component\\Silex\\Loader\\Pass\\RoutingPass'
    )
));

return $app;
