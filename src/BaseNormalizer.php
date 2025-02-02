<?php declare(strict_types=1);

namespace AP\Normalizer;

use UnexpectedValueException;

class BaseNormalizer implements Normalizer
{
    /**
     * @var array<Normalizer>
     */
    protected array $normalizers = [];

    /**
     * @param array<Normalizer> $normalizers
     */
    public function __construct(array $normalizers = [])
    {
        foreach ($normalizers as $normalizer) {
            if ($normalizer instanceof Normalizer) {
                $this->appendNormalizer($normalizer);
            } else {
                throw new UnexpectedValueException("normalizer must implement Normalizer interface");
            }
        }
    }

    /**
     * @return array<Normalizer>
     */
    final public function getNormalizers(): array
    {
        return $this->normalizers;
    }

    /**
     * Adds a formatter to the list of formatters.
     *
     * @param Normalizer $normalizer
     * @return static
     */
    final public function appendNormalizer(Normalizer $normalizer): static
    {
        $this->normalizers[] = $normalizer;
        return $this;
    }

    /**
     * Prepends a formatter to the list, ensuring it is applied first.
     *
     * @param Normalizer $normalizer
     * @return static
     */
    final public function prependNormalizer(Normalizer $normalizer): static
    {
        $this->normalizers = array_merge([$normalizer], $this->normalizers);
        return $this;
    }

    public function normalize(mixed $value): ?Normalized
    {
        if (is_string($value) || is_int($value) || is_float($value) || is_bool($value) || is_null($value)) {
            return new Normalized($value);
        } elseif (is_array($value)) {
            foreach ($value as $k => $v) {
                $v = $this->normalize($v);
                if ($v instanceof Normalized) {
                    $value[$k] = $v->value;
                } else {
                    unset($value[$k]);
                }
            }
            return new Normalized($value);
        }
        foreach ($this->normalizers as $normalizer) {
            $v = $normalizer->normalize($value);
            if ($v instanceof Normalized) {
                return $v;
            }
        }
        return null;
    }
}