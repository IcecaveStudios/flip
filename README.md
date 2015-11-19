# Flip

[![The most recent stable version is 0.2.0][version-image]][semantic versioning]
[![Current build status image][build-image]][current build status]
[![Current coverage status image][coverage-image]][current coverage status]

[build-image]: http://img.shields.io/travis/IcecaveStudios/flip/develop.svg?style=flat-square "Current build status for the develop branch"
[current build status]: https://travis-ci.org/IcecaveStudios/flip
[coverage-image]: https://img.shields.io/codecov/c/github/IcecaveStudios/flip/develop.svg?style=flat-square "Current test coverage for the develop branch"
[current coverage status]: https://coveralls.io/r/IcecaveStudios/flip
[semantic versioning]: http://semver.org/
[version-image]: http://img.shields.io/:semver-0.2.0-yellow.svg?style=flat-square "This project uses semantic versioning"

**Flip** is a tiny PHP library for working with strict sets of boolean flags.

- Install via [Composer] package [icecave/flip]
- Read the [API documentation]

[api documentation]: http://icecavestudios.github.io/flip/artifacts/documentation/api/
[composer]: http://getcomposer.org/
[icecave/flip]: https://packagist.org/packages/icecave/flip

## Defining a flag-set

A flag-set describes the available flags of a given type. Flag-sets are defined
by declaring a class that uses the `FlagSetTrait` trait.

Each property in the class defines a named flag that can be set to `true` or
`false`. All properties must be private and have a default boolean value.

```php
use Icecave\Flip\FlagSetTrait;

final class ExampleFlags
{
    use FlagSetTrait;

    private $foo = true;
    private $bar = false;
    private $baz = false;
}
```

## Creating a flag-set

The flag-set trait provides the following static methods for quickly creating
common sets:

* `defaults()` - creates a flag-set where all flags are set to the default values
* `all()` - creates a flag-set where all flags are set to `true`
* `none()` - creates a flag-set where all flags are set to `false`

Flag-sets can also be created and modified using a fluent interface. The example
below creates a flag-set with only the `bar` and `baz` properties set to `true`.

```php
$flags = ExampleFlags::none()
    ->bar(true)
    ->baz(true);
```

Omitting the initial call to `defaults()`, `all()` or `none()` is short-hand
for using the defaults. This means that the following two examples are equivalent:

```php
$flags = ExampleFlags::defaults()
    ->foo(false)
    ->bar(true);
```

```php
$flags = ExampleFlags
    ::foo(false)
    ->bar(true);
```

Flag-sets are immutable, each call to the fluent interface returns a new
instance with the updated flag value.

Flags can not be named "defaults", "all" or "none".

## Using a flag-set

**Functions that accept flag-sets as parameters can use a type-hint.** Flags are
read using the regular PHP property notation. Flag values are guaranteed to be a
boolean.

```php
function dumpFlags(ExampleFlags $flags)
{
    if ($flags->foo) {
        echo 'Foo is enabled!';
    } else {
        echo 'Foo is disabled!';
    }

    if ($flags->bar) {
        echo 'Bar is enabled!';
    } else {
        echo 'Bar is disabled!';
    }

    if ($flags->baz) {
        echo 'Baz is enabled!';
    } else {
        echo 'Baz is disabled!';
    }
}
```

It is not possible to set flags using the property notation.

## Contact us

- Follow [@IcecaveStudios] on Twitter
- Visit the [Icecave Studios website]

[@icecavestudios]: https://twitter.com/IcecaveStudios
[icecave studios website]: http://icecave.com.au/
