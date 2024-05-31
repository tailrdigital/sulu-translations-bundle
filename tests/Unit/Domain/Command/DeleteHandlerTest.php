<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Tailr\SuluTranslationsBundle\Domain\Command\DeleteCommand;
use Tailr\SuluTranslationsBundle\Domain\Command\DeleteHandler;
use Tailr\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationDeletedEvent;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Domain\Time\Clock;

class DeleteHandlerTest extends TestCase
{
    use ProphecyTrait;

    private Clock|ObjectProphecy $clock;
    private TranslationRepository|ObjectProphecy $repository;
    private EventDispatcher|ObjectProphecy $eventDispatcher;
    private DeleteHandler $handler;

    protected function setUp(): void
    {
        $this->clock = $this->prophesize(Clock::class);
        $this->repository = $this->prophesize(TranslationRepository::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcher::class);

        $this->handler = new DeleteHandler(
            $this->clock->reveal(),
            $this->repository->reveal(),
            $this->eventDispatcher->reveal(),
        );
    }

    /** @test */
    public function it_can_remove_a_translation_record(): void
    {
        $command = new DeleteCommand($key = 'key', $locale = 'en', $domain = 'domain');
        $this->repository->deleteByKeyLocaleDomain(
            $key,
            $locale,
            $domain
        )->shouldBeCalled();
        $this->clock->now()
            ->willReturn($now = new \DateTimeImmutable());
        $this->eventDispatcher
            ->dispatch(Argument::that(fn (TranslationDeletedEvent $event) => $event->translationKey === $key
                && $event->locale === $locale
                && $event->domain === $domain
                && $event->removedAt === $now))
            ->shouldBeCalled();

        ($this->handler)($command);
    }
}
