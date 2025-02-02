<?php declare(strict_types=1);

namespace AP\Normalizer\Tests;

use AP\Normalizer\BaseNormalizer;
use AP\Normalizer\ThrowableNormalizer;
use Exception;
use PHPUnit\Framework\TestCase;

final class ReadmeTest extends TestCase
{

    public function testSpecialNormalizer(): void
    {
        $normalizer = new BaseNormalizer([
            new ThrowableNormalizer(include_trace: false)
        ]);

        $exception = new Exception("file not found", 1543);

        $normalizedObject = $normalizer->normalize([
            "message"   => "some error message",
            "exception" => $exception,
        ]);

        $normalizedArray = $normalizedObject->value;

        // because filename can be different remove it before check result
        unset($normalizedArray['exception']['file']);

        $this->assertEquals(
            [
                'message'   => 'some error message',
                'exception' =>
                    [
                        'type'    => 'Exception',
                        'message' => 'file not found',
                        // 'file'    => '/code/normalizer/tests/ReadmeTest.php',
                        'line'    => 19,
                        'code'    => 1543,
                    ],
            ],
            $normalizedArray
        );
    }


}
