<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Controller\Admin;

use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Tailr\SuluTranslationsBundle\Infrastructure\Sulu\Admin\TranslationsAdmin;

abstract class AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function getSecurityContext(): string
    {
        return TranslationsAdmin::SECURITY_CONTEXT;
    }

    public function getLocale(Request $request): string
    {
        return $request->getLocale();
    }
}
