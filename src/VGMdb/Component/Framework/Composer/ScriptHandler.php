<?php

namespace VGMdb\Component\Framework\Composer;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\CommandEvent;

class ScriptHandler
{
    /**
     * Clears the cache.
     *
     * @param $event CommandEvent A instance
     */
    public static function clearCache(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['app-dir'];

        if (!is_dir($appDir)) {
            echo 'The app-dir ('.$appDir.') specified in composer.json was not found in '.getcwd().', cannot clear the cache.'.PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'cache:clear --no-warmup', $options['process-timeout']);
    }

    /**
     * Installs the assets under the web root directory.
     *
     * For better interoperability, assets are copied instead of symlinked on Windows.
     *
     * @param $event CommandEvent A instance
     */
    public static function installAssets(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['app-dir'];
        $webDir = $options['web-dir'];

        $symlink = '';
        if ($options['assets-install'] == 'symlink') {
            $symlink = '--symlink ';
        } elseif ($options['assets-install'] == 'relative') {
            $symlink = '--symlink --relative ';
        }

        if (!is_dir($webDir)) {
            echo 'The web-dir ('.$webDir.') specified in composer.json was not found in '.getcwd().', cannot install assets.'.PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'assets:install '.$symlink.escapeshellarg($webDir));
    }

    /**
     * Update the app files.
     *
     * @param $event CommandEvent A instance
     */
    public static function installAppFiles(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['app-dir'];
        $webDir = $options['web-dir'];

        if (!is_dir($appDir)) {
            echo 'The app-dir ('.$appDir.') specified in composer.json was not found in '.getcwd().', cannot install app files.'.PHP_EOL;

            return;
        }

        if (!is_dir($webDir)) {
            echo 'The web-dir ('.$webDir.') specified in composer.json was not found in '.getcwd().', cannot install app files.'.PHP_EOL;

            return;
        }

        copy(dirname(__DIR__).'/Resources/skeleton/app/app.php', $appDir.'/app.php');
        copy(dirname(__DIR__).'/Resources/skeleton/app/bootstrap.php', $appDir.'/bootstrap.php');
        copy(dirname(__DIR__).'/Resources/skeleton/app/check.php', $appDir.'/check.php');
        copy(dirname(__DIR__).'/Resources/skeleton/app/console', $appDir.'/console');

        copy(dirname(__DIR__).'/Resources/skeleton/public/index.php', $webDir.'/index.php');
        copy(dirname(__DIR__).'/Resources/skeleton/public/index_test.php', $webDir.'/index_test.php');

        chmod($appDir.'/console', 0755);
    }

    protected static function executeCommand(CommandEvent $event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) { echo $buffer; });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd)));
        }
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(array(
            'app-dir' => 'app',
            'web-dir' => 'public',
            'assets-install' => 0 === stripos(PHP_OS, 'WIN') ? 'hard' : 'symlink'
        ), $event->getComposer()->getPackage()->getExtra());

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder;
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }
}
