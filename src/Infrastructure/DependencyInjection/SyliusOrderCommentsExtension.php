<?php

namespace Sylius\OrderCommentsPlugin\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SyliusOrderCommentsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        /** @var string $projectDir */
        $projectDir = $container->getParameter('kernel.project_dir');
        $container->setParameter(
            'sylius_order_comment_plugin.comment_file_dir',
            $projectDir.'/public/media/comment_attachments'
        );
    }
}
