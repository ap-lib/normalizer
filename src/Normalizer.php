<?php declare(strict_types=1);

namespace AP\Normalizer;

interface Normalizer
{
    public function normalize(mixed $value): ?Normalized;
}