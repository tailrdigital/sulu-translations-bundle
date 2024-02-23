<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\ListBuilderInterface;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\RestHelperInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Infrastructure\Sulu\Admin\TranslationsAdmin;
use Tailr\SuluTranslationsBundle\Presentation\Controller\Admin\ListController;

class ListControllerTest extends TestCase
{
    use ProphecyTrait;

    private ViewHandlerInterface|ObjectProphecy $viewHandler;
    private DoctrineListBuilderFactoryInterface|ObjectProphecy $listBuilderFactory;
    private FieldDescriptorFactoryInterface|ObjectProphecy $fieldDescriptorFactory;
    private RestHelperInterface|ObjectProphecy $restHelper;
    private ListController $controller;

    protected function setUp(): void
    {
        $this->viewHandler = $this->prophesize(ViewHandlerInterface::class);
        $this->listBuilderFactory = $this->prophesize(DoctrineListBuilderFactoryInterface::class);
        $this->fieldDescriptorFactory = $this->prophesize(FieldDescriptorFactoryInterface::class);
        $this->restHelper = $this->prophesize(RestHelperInterface::class);

        $this->controller = new ListController(
            $this->viewHandler->reveal(),
            $this->listBuilderFactory->reveal(),
            $this->fieldDescriptorFactory->reveal(),
            $this->restHelper->reveal(),
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
    public function it_can_fetch_translations_and_return_a_list_response(): void
    {
        $listBuilder = $this->prophesize(ListBuilderInterface::class);
        $this->listBuilderFactory->create(Translation::class)
            ->willReturn($listBuilder->reveal());
        $this->fieldDescriptorFactory->getFieldDescriptors(TranslationsAdmin::LIST_KEY)->willReturn([]);
        $this->restHelper->initializeListBuilder($listBuilder->reveal(), [])->shouldBeCalled();
        $listBuilder->execute()
            ->willReturn($data = [['id' => 1, 'key' => 'app.foo.bar']]);
        $listBuilder->getCurrentPage()->willReturn(1);
        $listBuilder->getLimit()->willReturn(10);
        $listBuilder->count()->willReturn(20);

        $expectedResponse = $this->prophesize(Response::class);
        $this->viewHandler->handle(Argument::that(fn (View $view) => $view->getData()->getData() === $data))
            ->willReturn($expectedResponse);

        $response = ($this->controller)(new Request());

        $this->assertEquals($expectedResponse->reveal(), $response);

    }
}
