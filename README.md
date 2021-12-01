# Sylius Order Comments Plugin
![example workflow](https://github.com/Brille24/SyliusOrderCommentsPlugin/actions/workflows/build.yml/badge.svg)

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
