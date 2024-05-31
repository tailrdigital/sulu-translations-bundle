<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportCommand;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportHandler;
use Tailr\SuluTranslationsBundle\Presentation\Controller\Admin\ExportController;

class ExportControllerTest extends TestCase
{
    use ProphecyTrait;

    private ExportHandler|ObjectProphecy $handler;
    private ExportController $controller;

    protected function setUp(): void
    {
        $this->handler = $this->prophesize(ExportHandler::class);
        $this->controller = new ExportController(
            $this->handler->reveal(),
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
    public function it_can_export_translations(): void
    {
        $this->handler
            ->__invoke(new ExportCommand())
            ->willReturn('OK')
            ->shouldBeCalled();

        $response = ($this->controller)();
        self::assertSame('{"message":"OK"}', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
}
