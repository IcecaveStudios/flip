<?php
namespace Icecave\Flip;

final class TestOptionsWithNonPrivateProperty
{
    use OptionSetTrait;

    public $foo = true;
}
