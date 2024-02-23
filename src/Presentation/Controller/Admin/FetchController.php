<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Controller\Admin;

use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;

#[Route(path: '/translations/{id}', name: 'tailr.translations_fetch', options: ['expose' => true], methods: ['GET'])]
final class FetchController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly TranslationRepository $repository,
        private readonly TranslationSerializer $serializer,
    ) {
    }

    public function __invoke(int $id): Response
    {
        return new JsonResponse(
            ($this->serializer)($this->repository->findById($id))
        );
    }
}
