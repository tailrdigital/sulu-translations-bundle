<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Clock\ClockInterface;
use Tailr\SuluTranslationsBundle\Domain\Command\UpdateHandler;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class UpdateHandlerTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private ClockInterface|ObjectProphecy $clock;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);
        $this->clock = $this->prophesize(ClockInterface::class);
        $this->handler = new UpdateHandler(
            $this->repository->reveal(),
            $this->clock->reveal(),
        );
    }

    /** @test */
    public function it_cat_update_a_translation_record(): void
    {
        $this->repository->findById($id = 1)
            ->willReturn($translation = Translations::create());
        $this->clock->now()
            ->willReturn($updatedAt = new \DateTimeImmutable());
        $this->repository->save($translation)->shouldBeCalled();

        ($this->handler)($id, $translationValue = 'Updated value');
        self::assertSame($updatedAt, $translation->getUpdatedAt());
        self::assertSame($translationValue, $translation->getTranslation());
    }
}
