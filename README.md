The VGMdb Framework
===================

An application skeleton based on the [Silex] [1] microframework.

## Installation

Create a `composer.json` file and use [Composer] [2] to install it:

    {
        "name": "vgmdb/foobar",
        "type": "project",
        "repositories": [
            {
                "type": "vcs",
                "url": "http://github.com/vgmdb/common"
            },
            {
                "type": "vcs",
                "url": "http://github.com/vgmdb/framework"
            },
            {
                "type": "vcs",
                "url": "http://github.com/gigablah/mustache.php"
            },
            {
                "type": "vcs",
                "url": "http://github.com/gigablah/PHPUnit_Html"
            },
            {
                "type": "vcs",
                "url": "http://github.com/gigablah/mysql-workbench-schema-exporter"
            }
        ],
        "autoload": {
            "psr-0": {
                "": "src/"
            }
        },
        "require": {
            "vgmdb/common": "dev-master",
            "vgmdb/framework": "dev-master"
        },
        "require-dev": {
            "phpunit/phpunit": "3.7.*",
            "gigablah/phpunit-html": "dev-master",
            "nikic/php-parser": "0.9.*",
            "mwbexporter/mwbexporter": "dev-master"
        },
        "scripts": {
            "post-install-cmd": [
                "VGMdb\\Component\\Framework\\Composer\\ScriptHandler::installAppFiles",
                "VGMdb\\Component\\Framework\\Composer\\ScriptHandler::installAssets",
                "VGMdb\\Component\\Framework\\Composer\\ScriptHandler::clearCache"
            ],
            "post-update-cmd": [
                "VGMdb\\Component\\Framework\\Composer\\ScriptHandler::installAppFiles",
                "VGMdb\\Component\\Framework\\Composer\\ScriptHandler::installAssets",
                "VGMdb\\Component\\Framework\\Composer\\ScriptHandler::clearCache"
            ]
        },
        "minimum-stability": "dev",
        "extra": {
            "app-dir": "app",
            "web-dir": "public"
        }
    }

## Developer Guidelines

Adhere to the [PSR-1] [3] and [PSR-2] [4] coding standards.

## License

Licensed under the MIT license.

[1]: https://github.com/fabpot/Silex
[2]: http://getcomposer.org
[3]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
