<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Controller\Admin;

use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Tailr\SuluTranslationsBundle\Domain\Command\UpdateHandler;
use Tailr\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;

#[Route(path: '/translations/{id}', name: 'tailr.translations_update', methods: ['PUT'])]
final class UpdateController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly UpdateHandler $handler,
        private readonly TranslationSerializer $serializer,
    ) {
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        return new JsonResponse(
            ($this->serializer)(
                ($this->handler)(
                    $id,
                    $request->get('translation'),
                ),
            )
        );
    }
}
