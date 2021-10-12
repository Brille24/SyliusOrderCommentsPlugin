> :warning: **BEWARE!**
> This repository has been deprecated and will not be maintained or evolved by the Sylius Team. You can still use it with compatible Sylius versions, but at your own risk, as no bugs will be fixed on it.

# Sylius Order Comments Plugin

[![Build Status](https://travis-ci.com/Sylius/SyliusOrderCommentsPlugin.svg?token=8ZLRHEY2aPJvQgqmQCxh&branch=master)](https://travis-ci.com/Sylius/SyliusOrderCommentsPlugin)

## Installation

1. Move migrations from `test/Application` to your migrations folder and run `php bin/console doctrine:migrations:migrate` to update your database.

## Testing & Development

In order to run tests, execute following commands:

```bash
$ composer install
$ cd tests/Application
$ bin/console doctrine:schema:create --env test
$ vendor/bin/behat
$ vendor/bin/phpunit
$ vendor/bin/phpspec
```

In order to open test app in your browser, do the following:

```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn run gulp
$ bin/console doctrine:schema:create --env test
$ bin/console server:start --env test
$ open http://127.0.0.1:8000/
```

