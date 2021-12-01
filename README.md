<h1 align="center">Plugin Skeleton</h1>

<p align="center">Skeleton for starting Sylius plugins.</p>

![Workflow](https://github.com/Brille24/SyliusOrderCommentsPlugin/actions/workflows/build.yml/badge.svg)

## Installation
1. Run `composer require brille24/sylius-order-comments-plugin`
2. Updating the database with migrations
```bash
bin/console doctrine:mig:diff
bin/console doctrine:mig:mig
```

## Testing & Development
For information on how to develop to test this plugin please refer to the official Sylius documentation under https://github.com/Sylius/PluginSkeleton/ .
