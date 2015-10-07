<?php
namespace Icecave\Flip;

final class TestOptions extends AbstractOptions
{
    const FOO = 'foo';
    const BAR = 'bar';
    const BAZ = 'baz';
    const QUX = 'qux';

    public static function defaults()
    {
        return [
            self::FOO => true,
            self::BAZ => false,
        ];
    }
}
