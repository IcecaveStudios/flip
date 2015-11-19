<?php

namespace Icecave\Flip;

final class TestFlagsWithNonBooleanProperty
{
    use FlagSetTrait;

    private $foo = '<string>';
}
