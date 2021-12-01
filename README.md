<h1 align="center">Plugin Skeleton</h1>

<p align="center">Adding comments to a Sylius order with customer notification.</p>

![Workflow](https://github.com/Brille24/SyliusOrderCommentsPlugin/actions/workflows/build.yml/badge.svg)

![UI Example](https://user-images.githubusercontent.com/14860264/144292467-52745b86-dfa1-467f-98e7-f4311a8b86b3.png)


## Installation
1. Run `composer require brille24/sylius-order-comments-plugin`
2. Updating the database with migrations
```bash
bin/console doctrine:mig:diff
bin/console doctrine:mig:mig
```

## Testing & Development
For information on how to develop to test this plugin please refer to the official Sylius documentation under https://github.com/Sylius/PluginSkeleton/ .
