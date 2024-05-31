<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Controller\Admin;

use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Tailr\SuluTranslationsBundle\Domain\Command\UpdateCommand;
use Tailr\SuluTranslationsBundle\Domain\Command\UpdateHandler;
use Tailr\SuluTranslationsBundle\Domain\Query\FetchTranslation;
use Tailr\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;

use function Psl\Type\non_empty_string;

#[Route(path: '/translations/{id}', name: 'tailr.translations_update', options: ['expose' => true], methods: ['PUT'])]
final class UpdateController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly UpdateHandler $handler,
        private readonly FetchTranslation $fetchTranslation,
        private readonly TranslationSerializer $serializer,
    ) {
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        ($this->handler)(
            new UpdateCommand($id, non_empty_string()->coerce($request->request->get('translation')))
        );

        return new JsonResponse(
            ($this->serializer)(
                ($this->fetchTranslation)($id)
            )
        );
    }
}
