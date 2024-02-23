<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psl\Type\Exception\CoercionException;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Tailr\SuluTranslationsBundle\Domain\Command\UpdateHandler;
use Tailr\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;
use Tailr\SuluTranslationsBundle\Presentation\Controller\Admin\UpdateController;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class UpdateControllerTest extends TestCase
{
    use ProphecyTrait;

    private UpdateHandler|ObjectProphecy $handler;
    private TranslationSerializer|ObjectProphecy $serializer;
    private UpdateController $controller;

    protected function setUp(): void
    {
        $this->handler = $this->prophesize(UpdateHandler::class);
        $this->serializer = $this->prophesize(TranslationSerializer::class);
        $this->controller = new UpdateController(
            $this->handler->reveal(),
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
    public function it_can_update_a_translation_value_of_a_translation_record(): void
    {
        $this->handler
            ->__invoke($id = 1, $translationValue = 'Some updated value')
            ->willReturn($translation = Translations::create())
            ->shouldBeCalled();

        $this->serializer->__invoke($translation)
            ->willReturn(['id' => $id])
            ->shouldBeCalled();

        $response = ($this->controller)($id, new Request(request: ['translation' => $translationValue]));
        self::assertSame('{"id":1}', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function it_expects_an_translation_value(): void
    {
        self::expectException(CoercionException::class);

        ($this->controller)(1, new Request(request: ['translation' => '']));
    }
}
