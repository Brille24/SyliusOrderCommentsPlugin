# Sylius Order Comments Plugin

[![Build Status](https://travis-ci.com/Sylius/SyliusOrderCommentsPlugin.svg?token=8ZLRHEY2aPJvQgqmQCxh&branch=master)](https://travis-ci.com/Sylius/SyliusOrderCommentsPlugin)

## Testing & Development

In order to run tests, execute following commands:

```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn run gulp
$ bin/console doctrine:database:create --env test
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
$ bin/console doctrine:database:create --env test
$ bin/console doctrine:schema:create --env test
$ bin/console server:start --env test
$ open http://127.0.0.1:8000/
```
