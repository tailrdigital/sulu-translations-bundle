<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Component\Rest\ListBuilder\ListRestHelperInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Tailr\SuluTranslationsBundle\Domain\Query\FetchTranslations;
use Tailr\SuluTranslationsBundle\Domain\Query\SearchCriteria;
use Tailr\SuluTranslationsBundle\Presentation\Controller\Admin\ListController;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class ListControllerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|SerializerInterface $serializer;
    private ObjectProphecy|ListRestHelperInterface $listRestHelper;
    private FetchTranslations|ObjectProphecy $fetchTranslations;
    private ListController $controller;

    protected function setUp(): void
    {
        $this->serializer = $this->prophesize(SerializerInterface::class);
        $this->listRestHelper = $this->prophesize(ListRestHelperInterface::class);
        $this->fetchTranslations = $this->prophesize(FetchTranslations::class);

        $this->controller = new ListController(
            $this->serializer->reveal(),
            $this->listRestHelper->reveal(),
            $this->fetchTranslations->reveal(),
        );
    }

    /** @test */
    public function it_is_a_secured_controller(): void
    {
        self::assertInstanceOf(SecuredControllerInterface::class, $this->controller);
        self::assertSame('tailr_translations', $this->controller->getSecurityContext());
        self::assertSame('en', $this->controller->getLocale(new Request()));
    }

    /** @test */
    public function it_can_fetch_a_paginated_list(): void
    {
        $this->listRestHelper->getSortColumn()->willReturn(null);
        $this->listRestHelper->getSortOrder()->willReturn(null);
        $this->listRestHelper->getSearchPattern()->willReturn(null);
        $this->listRestHelper->getPage()->willReturn(1);
        $this->listRestHelper->getLimit()->willReturn($limit = 2);
        $this->listRestHelper->getOffset()->willReturn($offset = 0);

        $this->fetchTranslations->__invoke(new SearchCriteria(
            '',
            null,
            null,
            $offset,
            $limit
        ))->willReturn(
            [
                Translations::create('Foo'),
                Translations::create('Bar'),
            ]
        )->shouldBeCalledOnce();

        $this->serializer->serialize(Argument::type('array'), 'json')
            ->willReturn($serializedData = '{"_embedded": {"tailr_translations": []}, "limit": 10, "total": 2, "page": 1, "pages": 1}');

        $response = ($this->controller)();

        self::assertSame($serializedData, $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function it_can_fetch_a_paginated_filtered_and_sorted_list(): void
    {
        $this->listRestHelper->getSortColumn()->willReturn($sortColumn = 'id');
        $this->listRestHelper->getSortOrder()->willReturn($sortOrder = 'ASC');
        $this->listRestHelper->getSearchPattern()->willReturn($searchPattern = 'Foo');
        $this->listRestHelper->getPage()->willReturn(1);
        $this->listRestHelper->getLimit()->willReturn($limit = 2);
        $this->listRestHelper->getOffset()->willReturn($offset = 0);

        $this->fetchTranslations->__invoke(new SearchCriteria(
            $searchPattern,
            $sortColumn,
            $sortOrder,
            $offset,
            $limit
        ))->willReturn(
            [
                Translations::create('Foo'),
            ]
        )->shouldBeCalledOnce();

        $this->serializer->serialize(Argument::type('array'), 'json')
            ->willReturn($serializedData = '{"_embedded": {"tailr_translations": []}, "limit": 10, "total": 1, "page": 1, "pages": 1}');

        $response = ($this->controller)();

        self::assertSame($serializedData, $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
}
