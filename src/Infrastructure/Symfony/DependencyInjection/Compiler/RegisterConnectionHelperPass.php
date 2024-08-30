<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\DependencyInjection\Compiler;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\DatabaseConnectionManager;

class RegisterConnectionHelperPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        //        $container
        //            ->register('tailr_translations.database_connection_manager', DatabaseConnectionManager::class)
        //            ->addArgument(new Reference('translation.provider_collection'))
        //            ->addArgument(new Reference(ManagerRegistry::class));

    }
}
