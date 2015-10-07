# Flip

[![The most recent stable version is 0.0.0][version-image]][semantic versioning]
[![Current build status image][build-image]][current build status]
[![Current coverage status image][coverage-image]][current coverage status]

[build-image]: http://img.shields.io/travis/IcecaveStudios/flip/develop.svg?style=flat-square "Current build status for the develop branch"
[current build status]: https://travis-ci.org/IcecaveStudios/flip
[coverage-image]: https://img.shields.io/codecov/c/github/IcecaveStudios/flip/develop.svg?style=flat-square "Current test coverage for the develop branch"
[current coverage status]: https://coveralls.io/r/IcecaveStudios/flip
[semantic versioning]: http://semver.org/
[version-image]: http://img.shields.io/:semver-0.0.0-red.svg?style=flat-square "This project uses semantic versioning"

**Flip** is a tiny PHP library for working with strict sets of boolean values.

- Install via [Composer] package [icecave/flip]
- Read the [API documentation]

[api documentation]: http://icecavestudios.github.io/flip/artifacts/documentation/api/
[composer]: http://getcomposer.org/
[icecave/flip]: https://packagist.org/packages/icecave/flip

## Defining an option-set

An option-set describes the available options of a given type. Option-sets are
defined by declaring a class that uses the `OptionSetTrait` trait.

Each property in the class defines a named option that be set to `true` or
`false`. All properties must be private and have a default boolean value.

```php
use Icecave\Flip\OptionSetTrait;

final class ExampleOptions
{
    use OptionSetTrait;

    private $foo = true;
    private $bar = false;
    private $baz = false;
}
```

## Creating an option-set

The option-set trait provides the following static methods for quickly creating
common sets:

* `defaults()` - creates an option-set where all options are set to the default values
* `all()` - creates an option-set where all options are set to `true`
* `none()` - creates an option-set where all options are set to `false`

Option-sets can also be created and modified using a fluent interface. The
example below creates an option-set with only the `bar` and `baz` properties set
to `true`.

```php
$options = ExampleOptions::none()
    ->bar(true)
    ->baz(true);
```

Omitting the initial call to `defaults()`, `all()` or `none()` is short-hand
for using the defaults. This means that the following two examples are equivalent:

```php
$options = ExampleOptions::defaults()
    ->foo(false)
    ->bar(true);
```

```php
$options = ExampleOptions
    ::foo(false)
    ->bar(true);
```

Options can not be named "defaults", "all" or "none".

## Using an option-set

**Functions that accept option-sets as parameters can use a type-hint.** Options are
read using the regular PHP property notation. Option values are guaranteed to
be a boolean.

```php
function dumpOptions(ExampleOptions $options)
{
    if ($options->foo) {
        echo 'Foo is enabled!';
    } else {
        echo 'Foo is disabled!';
    }

    if ($options->bar) {
        echo 'Bar is enabled!';
    } else {
        echo 'Bar is disabled!';
    }

    if ($options->baz) {
        echo 'Baz is enabled!';
    } else {
        echo 'Baz is disabled!';
    }
}
```

Note that option-sets are immutable, and as such it is not possible to set
options using the property notation. Instead, change the option-set using the
fluent notation described above.

## Contact us

- Follow [@IcecaveStudios] on Twitter
- Visit the [Icecave Studios website]

[@icecavestudios]: https://twitter.com/IcecaveStudios
[icecave studios website]: http://icecave.com.au/
