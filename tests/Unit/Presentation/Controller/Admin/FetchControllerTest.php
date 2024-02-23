<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;
use Tailr\SuluTranslationsBundle\Presentation\Controller\Admin\FetchController;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class FetchControllerTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private TranslationSerializer|ObjectProphecy $serializer;
    private FetchController $controller;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);
        $this->serializer = $this->prophesize(TranslationSerializer::class);
        $this->controller = new FetchController(
            $this->repository->reveal(),
            $this->serializer->reveal()
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
    public function it_can_fetch_translation_record(): void
    {
        $this->repository->findById($id = 1)
            ->willReturn($translation = Translations::create())
            ->shouldBeCalled();
        $this->serializer->__invoke($translation)
            ->willReturn(['id' => $id])
            ->shouldBeCalled();

        $response = ($this->controller)($id);
        self::assertSame('{"id":1}', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
}
