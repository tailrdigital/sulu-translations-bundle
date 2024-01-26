<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Command;

use Symfony\Component\Clock\ClockInterface;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

use function Psl\Str\is_empty;

class UpdateHandler
{
    public function __construct(
        private readonly TranslationRepository $repository,
        private readonly ClockInterface $clock,
    ) {
    }

    public function __invoke(int $id, string $translationValue): Translation
    {
        if (is_empty($translationValue)) {
            throw new \RuntimeException('You need to provide an valid translation value.');
        }

        $translation = $this->repository->findById($id);
        $this->repository->save(
            $translation->patch(
                $translationValue,
                $this->clock->now()
            )
        );

        return $translation;
    }
}
