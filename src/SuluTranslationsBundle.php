<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\DependencyInjection\SuluTranslationsExtension;

class SuluTranslationsBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new SuluTranslationsExtension();
    }
}
