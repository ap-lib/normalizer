<?php declare(strict_types=1);

namespace AP\Normalizer\Tests;

use AP\Normalizer\BaseNormalizer;
use AP\Normalizer\Normalized;
use AP\Normalizer\ThrowableNormalizer;
use Exception;
use PHPUnit\Framework\TestCase;

final class BaseTest extends TestCase
{
    public function testBasic(): void
    {
        $normalizer = new BaseNormalizer();

        $test = [
            "int"    => 7,
            "float"  => 3.14,
            "string" => "some string",
            "bool"   => true,
            "null"   => null,
            "array"  => [
                "int"    => 8,
                "float"  => 2.41,
                "string" => "some string",
                "bool"   => true,
                "null"   => null,
                "array"  => [
                    "int"    => 9,
                    "float"  => 3.14,
                    "string" => "some string",
                    "bool"   => true,
                    "null"   => null,
                ]
            ]
        ];

        $result = $normalizer->normalize($test);


        $this->assertEquals(new Normalized($test), $result);
    }

    public function testRemoveNoAllowedElements(): void
    {
        $normalizer = new BaseNormalizer();

        $test = [
            "message"   => "some error message",
            "level"     => 2,
            "exception" => new Exception("file not found", 1543),
        ];

        $result = $normalizer->normalize($test);

        $this->assertEquals(
            new Normalized([
                "message" => "some error message",
                "level"   => 2,
            ]),
            $result
        );
    }

    public function testSpecialNormalizer(): void
    {
        $throwableNormalizer = new ThrowableNormalizer(include_trace: false,);
        $normalizer          = new BaseNormalizer([$throwableNormalizer]);
        $exception           = new Exception("file not found", 1543);

        $this->assertEquals(
            new Normalized([
                "message"   => "some error message",
                "exception" => $throwableNormalizer->normalizeThrowable($exception)
            ]),
            $normalizer->normalize([
                "message"   => "some error message",
                "exception" => $exception,
            ])
        );
    }


}
