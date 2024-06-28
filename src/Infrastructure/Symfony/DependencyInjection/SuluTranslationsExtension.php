<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use function Psl\Type\non_empty_string;
use function Psl\Type\shape;

class SuluTranslationsExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('doctrine')) {
            $container->prependExtensionConfig(
                'doctrine',
                [
                    'orm' => [
                        'mappings' => [
                            'SuluTranslationsBundle' => [
                                'type' => 'attribute',
                                'dir' => __DIR__.'/../../../Domain/Model',
                                'prefix' => 'Tailr\SuluTranslationsBundle\Domain\Model',
                                'alias' => 'SuluTranslationsBundle',
                            ],
                        ],
                    ],
                ]
            );
        }

        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig(
                'sulu_admin',
                [
                    'lists' => [
                        'directories' => [
                            __DIR__.'/../../../../config/lists',
                        ],
                    ],
                    'forms' => [
                        'directories' => [
                            __DIR__.'/../../../../config/forms',
                        ],
                    ],
                    'resources' => [
                        'tailr_translations' => [
                            'routes' => [
                                'list' => 'tailr.translations_list',
                                'detail' => 'tailr.translations_fetch',
                            ],
                        ],
                    ],
                ]
            );
        }

        if ($container->hasExtension('framework')) {
            $container->prependExtensionConfig(
                'framework',
                [
                    'translator' => [
                        'paths' => [
                            __DIR__.'/../../../../translations',
                        ],
                    ],
                ]
            );
        }

    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../../../config/services'));
        $loader->load('actions.yaml');
        $loader->load('commands.yaml');
        $loader->load('commands.yaml');
        $loader->load('console-commands.yaml');
        $loader->load('doctrine.yaml');
        $loader->load('events.yaml');
        $loader->load('query.yaml');
        $loader->load('controllers.yaml');
        $loader->load('repositories.yaml');
        $loader->load('serializers.yaml');
        $loader->load('sulu-admin.yaml');
        $loader->load('time.yaml');
        $loader->load('translation-provider.yaml');

        $configuration = new Configuration();
        $config = shape([
            'export_format' => non_empty_string(),
        ])->assert($this->processConfiguration($configuration, $configs));

        $container->setParameter('sulu_translations.export_format', $config['export_format']);
    }
}
