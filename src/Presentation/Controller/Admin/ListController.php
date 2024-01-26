<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Controller\Admin;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\ListBuilder\PaginatedRepresentation;
use Sulu\Component\Rest\RestHelperInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Infrastructure\Sulu\Admin\TranslationsAdmin;

#[Route(path: '/translations', name: 'tailr.translations_list', options: ['expose' => true], methods: ['GET'])]
final class ListController extends AbstractSecuredTranslationsController implements SecuredControllerInterface
{
    public function __construct(
        private readonly ViewHandlerInterface $viewHandler,
        private readonly DoctrineListBuilderFactoryInterface $listBuilderFactory,
        private readonly FieldDescriptorFactoryInterface $fieldDescriptorFactory,
        private readonly RestHelperInterface $restHelper,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $listBuilder = $this->listBuilderFactory->create(Translation::class);

        $this->restHelper->initializeListBuilder(
            $listBuilder,
            $this->fieldDescriptorFactory->getFieldDescriptors(TranslationsAdmin::LIST_KEY) ?: []
        );

        /** @psalm-suppress RedundantCastGivenDocblockType */
        $listRepresentation = new PaginatedRepresentation(
            $listBuilder->execute(),
            Translation::RESOURCE_KEY,
            (int) $listBuilder->getCurrentPage(),
            (int) $listBuilder->getLimit(),
            $listBuilder->count()
        );

        return $this->viewHandler->handle(View::create($listRepresentation)->setFormat('json'));
    }
}
