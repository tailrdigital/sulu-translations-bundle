<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Controller\Admin;

use Sulu\Component\Rest\ListBuilder\ListRestHelperInterface;
use Sulu\Component\Rest\ListBuilder\PaginatedRepresentation;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Query\FetchTranslations;
use Tailr\SuluTranslationsBundle\Domain\Query\SearchCriteria;

use function Psl\Type\int;

#[Route(path: '/translations', name: 'tailr.translations_list', options: ['expose' => true], methods: ['GET'])]
final class ListController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ListRestHelperInterface $listRestHelper,
        private readonly FetchTranslations $fetchTranslations,
    ) {
    }

    public function __invoke(): JsonResponse
    {

        $limit = int()->coerce($this->listRestHelper->getLimit());

        $translationsResult = ($this->fetchTranslations)(
            new SearchCriteria(
                (string) $this->listRestHelper->getSearchPattern(),
                $this->listRestHelper->getSortColumn(),
                $this->listRestHelper->getSortOrder(),
                (int) $this->listRestHelper->getOffset(),
                $limit
            )
        );

        $listRepresentation = new PaginatedRepresentation(
            $translationsResult,
            Translation::RESOURCE_KEY,
            (int) $this->listRestHelper->getPage(),
            $limit,
            100, // TODO replace with actual total count
        );

        return new JsonResponse(
            $this->serializer->serialize($listRepresentation->toArray(), 'json'),
            json: true
        );
    }
}
