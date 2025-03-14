# AP\Caster\Scheme

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

AP\Caster\Scheme is a plugins for [caster](https://github.com/ap-lib/caster) for casting objects based on [scheme](https://github.com/ap-lib/scheme)

## Installation

```bash
composer require ap-lib/caster-scheme
```

## Features

- Caster uses the `ToObject::toObject(mixed $data)` method to construct objects.


## Requirements

- PHP 8.3 or higher

## Getting started

Here's a quick example demonstrating how to use `AP\Caster`.

### Initialize the PrimaryCaster with SchemeCaster

```php
$toObject = new ToObject(
    new \AP\ToObject\ObjectParser\ByConstructor(),
    new PrimaryCaster([
        new SchemeCaster,
    ])
);

class Guid implements \AP\Scheme\ToObject
{
    public function __construct(public string $bites) {
    
    }

    public static function toObject(array|string|int|float|bool|null $data): static
    {
        if (is_string($data)) {
            return new static(pack("h*", str_replace('-', '', $data)));
        }
        throw ThrowableErrors::one('value is not valid uuid bytes');
    }
}

class Request {
    public function __constructor(
        public User $user,
        public Guid $guid, 
    ){}
}

$obj = $toObject->makeObject(
    [
        "user" => [
            "name" => "John", 
            "age" => 12
        ], 
        "guid" => "6B29FC40-CA47-1067-B31D-00DD010662DA"
    ],
    Request::class
);
```