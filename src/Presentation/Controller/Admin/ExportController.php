<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Controller\Admin;

use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportHandlerInterface;

#[Route(path: '/translations/export', name: 'tailr.translations_export', options: ['expose' => true], methods: ['POST'], priority: 10)]
final class ExportController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly ExportHandlerInterface $exportHandler,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['message' => ($this->exportHandler)()], 200);
    }
}
