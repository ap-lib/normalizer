<?php declare(strict_types=1);

namespace AP\Normalizer\Tests;

use AP\Normalizer\Normalized;
use PHPUnit\Framework\TestCase;

final class NormalizedTest extends TestCase
{
    public function testBasic(): void
    {
        $base = [
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

        $this->assertEquals(
            $base,
            (new Normalized($base))->value
        );
    }

    public function testSanitize(): void
    {
        $base = [
            "int"    => 7,
            "object" => new \Exception("hello world"),
            "array"  => [
                "object" => new \Exception("hello world2"),
            ]
        ];

        $this->assertEquals(
            [
                "int"    => 7,
                "object" => null,
                "array"  => [
                    "object" => null,
                ]
            ],
            (new Normalized($base))->value
        );
    }
}
