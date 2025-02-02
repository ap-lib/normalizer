# AP\Normalizer

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A library that normalizes some mixed variable to simple types: int, string, array, bool, null

## Installation

```bash
composer require ap-lib/normalizer
```

## Features

- Allowed custom normalizers

## Requirements

- PHP 8.3 or higher

## Getting started

```php
$normalizer = new BaseNormalizer([
    new ThrowableNormalizer(include_trace: false)
]);

$normalizedObject = $normalizer->normalize([
    "message"   => "some error message",
    "exception" => new Exception("file not found", 1543),
]);

$normalizedArray = $normalizedObject->value;

var_export($normalizedArray);
/*
[
    'message'   => 'some error message',
    'exception' =>
        [
            'type'    => 'Exception',
            'message' => 'file not found',
            'file'    => '/code/path/to/file.php',
            'line'    => 19,
            'code'    => 1543,
        ],
]
*/
```