<?php

namespace Icecave\Flip;

final class TestFlagsWithNonPrivateProperty
{
    use FlagSetTrait;

    public $foo = true;
}
