<?php

namespace Brille24\OrderCommentsPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class Brille24SyliusOrderCommentsExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configs = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');

        /** @var string $projectDir */
        $projectDir = $container->getParameter('kernel.project_dir');
        $container->setParameter(
            'sylius_order_comment_plugin.comment_file_dir',
            $projectDir.'/public/media/comment_attachments'
        );
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    protected function getMigrationsNamespace(): string
    {
        return 'Brille24\SyliusOrderCommentsPlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@Brille24SyliusOrderCommentsPlugin/src/Infrastructure/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return ['Sylius\Bundle\CoreBundle\Migrations'];
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->getCurrentConfiguration($container);

        $this->registerResources('sylius', 'doctrine/orm', $config['resources'], $container);

        $this->prependDoctrineMigrations($container);
        $this->prependDoctrineMapping($container);
    }

    private function prependDoctrineMapping(ContainerBuilder $container): void
    {
        $config = array_merge(...$container->getExtensionConfig('doctrine'));

        // do not register mappings if dbal not configured.
        if (!isset($config['dbal']) || !isset($config['orm'])) {
            return;
        }

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'Brille24SyliusOrderCommentsPlugin' => [
                        'type' => 'xml',
                        'dir' => $this->getPath($container, '/config/doctrine/'),
                        'is_bundle' => false,
                        'prefix' => 'Brille24\OrderCommentsPlugin\Domain\Model',
                        'alias' => 'Brille24SyliusOrderCommentsPlugin',
                    ],
                ],
            ],
        ]);
    }

    private function getCurrentConfiguration(ContainerBuilder $container): array
    {
        /** @var ConfigurationInterface $configuration */
        $configuration = $this->getConfiguration([], $container);

        $configs = $container->getExtensionConfig($this->getAlias());

        return $this->processConfiguration($configuration, $configs);
    }

    private function getPath(ContainerBuilder $container, string $path): string
    {
        /** @var array<string, array<string, string>> $metadata */
        $metadata = $container->getParameter('kernel.bundles_metadata');

        return $metadata['Brille24SyliusOrderCommentsPlugin']['path'] . $path;
    }
}
