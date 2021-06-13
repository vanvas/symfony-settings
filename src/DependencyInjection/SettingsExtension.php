<?php
declare(strict_types=1);

namespace Vim\Settings\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Vim\Settings\Factory\SettingsCollectionFactory;
use Vim\Settings\Service\SettingsService;

class SettingsExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
        $config = $this->processConfiguration(new Configuration(), $configs);

        $container
            ->getDefinition(SettingsCollectionFactory::class)
            ->setArgument('$config', $config['settings'])
        ;

        $container
            ->getDefinition(SettingsService::class)
            ->setArgument('$cache', new Reference($config['pool']))
        ;
    }
}
