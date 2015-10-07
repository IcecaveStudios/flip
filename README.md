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

**Flip** is a simple PHP library for accepting sets of boolean options as input
to functions.

- Install via [Composer] package [icecave/flip]
- Read the [API documentation]

[api documentation]: http://icecavestudios.github.io/flip/artifacts/documentation/api/
[composer]: http://getcomposer.org/
[icecave/flip]: https://packagist.org/packages/icecave/flip

## Example

First the valid options are defined by declaring a class that extends from
`AbstractOptions`.

```php
use Icecave\Flip\AbstractOptions;

final class MyOptions extends AbstractOptions
{
    const FOO = 'foo';
    const BAR = 'bar';
}
```

Functions that accept the options use the `build()` method to create an
`OptionCollection` that holds the state of the passed options.

```php
function printOptions(array $options)
{
    $options = MyOptions::build($options);

    if ($options[MyOptions::FOO()]) {
        echo 'Foo is enabled!';
    } else {
        echo 'Foo is disabled!';
    }

    if ($options[MyOptions::BAR()]) {
        echo 'Bar is enabled!';
    } else {
        echo 'Bar is disabled!';
    }
}
```

When calling the function, options are passed as an array mapping the option
value to a boolean.

```php
$options = [
    MyOptions::FOO => true,
];

printOptions($options);
```

Which will output:
```console
Foo is enabled!
Bar is disabled!
```

An `InvalidArgumentException` is thrown if the options array passed to `build()`
contains keys that are not defined in the `MyOptions` class.

## Contact us

- Follow [@IcecaveStudios] on Twitter
- Visit the [Icecave Studios website]

[@icecavestudios]: https://twitter.com/IcecaveStudios
[icecave studios website]: http://icecave.com.au/
