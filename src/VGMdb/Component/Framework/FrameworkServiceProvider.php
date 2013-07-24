<?php

namespace VGMdb\Component\Framework;

use VGMdb\Component\Silex\AbstractResourceProvider;
use VGMdb\Component\Silex\Loader\YamlFileLoader;
use VGMdb\Component\Silex\Loader\CachedYamlFileLoader;
use VGMdb\Component\HttpFoundation\Request;
use VGMdb\Component\HttpFoundation\Response;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a framework skeleton and automatic configuration loading.
 *
 * @author Gigablah <gigablah@vgmdb.net>
 */
class FrameworkServiceProvider extends AbstractResourceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['framework.controller.exception'] = $app->protect(function (Request $request) use ($app) {
            if (!$request->attributes->get('exception')) {
                throw new NotFoundHttpException();
            }

            return new Response($app['view']($request->attributes->get('_view')));
        });

        $app['framework.loader.passes'] = array();
    }

    public function load(Application $app)
    {
        $options = array(
            'debug' => $app['debug'],
            'cache' => $app['cache'],
            'parameters' => array(
                'app.base_dir'  => $app['base_dir'],
                'app.cache_dir' => $app['cache_dir'],
                'app.log_dir'   => $app['log_dir'],
                'app.env'       => $app['env'],
                'app.debug'     => $app['debug'],
                'app.name'      => $app['name']
            ),
            'cache_dir' => $app['framework.cache_dir'],
            'cache_class' => $app['framework.cache_class'],
            'config_dirs' => $app['framework.config_dirs'],
            'config_file' => $app['framework.config_file']
        );

        $paths = array_merge($options['config_dirs'], array(
            $this->getPath() . '/Resources/config'
        ));

        $app['framework.loader.default'] = new YamlFileLoader($app, new FileLocator($paths), $options);
        $app['framework.loader.cache'] = new CachedYamlFileLoader($app, new FileLocator($paths), $options);

        foreach ($app['framework.loader.passes'] as $passClass) {
            $pass = new $passClass();
            $app['framework.loader.default']->addConfigPass($pass);
            $app['framework.loader.cache']->addConfigPass($pass);
        }

        if ($app['cache']) {
            $app['framework.loader.cache']->apply($app['framework.loader.cache']->load($options['config_file']));
        } else {
            $app['framework.loader.default']->apply($app['framework.loader.default']->load($options['config_file']));
        }
    }

    public function isAutoload()
    {
        return true;
    }

    public function boot(Application $app)
    {
    }
}
