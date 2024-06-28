<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Controller\Admin;

use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportCommand;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportHandler;

#[Route(path: '/translations/export', name: 'tailr.translations_export', options: ['expose' => true], methods: ['POST'], priority: 10)]
final class ExportController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly ExportHandler $exportHandler,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['message' => ($this->exportHandler)(new ExportCommand())], 200);
    }
}
