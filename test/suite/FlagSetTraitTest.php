<?php

namespace Icecave\Flip;

use LogicException;
use PHPUnit_Framework_TestCase;

class FlagSetTraitTest extends PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $flags = TestFlags::defaults();

        $this->assertTrue($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);
    }

    public function testAll()
    {
        $flags = TestFlags::all();

        $this->assertTrue($flags->foo);
        $this->assertTrue($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertTrue($flags->qux);
    }

    public function testNone()
    {
        $flags = TestFlags::none();

        $this->assertFalse($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertFalse($flags->baz);
        $this->assertFalse($flags->qux);
    }

    public function testFluent()
    {
        $flags = TestFlags::bar(true);

        // defaults + bar ...
        $this->assertTrue($flags->foo);
        $this->assertTrue($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);

        // change a single flag ...
        $new = $flags->foo(false);
        $this->assertFalse($new->foo);
        $this->assertTrue($new->bar);
        $this->assertTrue($new->baz);
        $this->assertFalse($new->qux);

        // original is unchanged ...
        $this->assertTrue($flags->foo);
        $this->assertTrue($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);
    }

    public function testSetUnknownFlagViaCallStatic()
    {
        $this->setExpectedException(
            LogicException::class,
            'The flag-set Icecave\Flip\TestFlags does not have a flag named "unknown".'
        );

        TestFlags::unknown(true);
    }

    public function testSetUnknownFlagViaCall()
    {
        $this->setExpectedException(
            LogicException::class,
            'The flag-set Icecave\Flip\TestFlags does not have a flag named "unknown".'
        );

        TestFlags::defaults()->unknown(true);
    }

    public function testGetUnknownFlag()
    {
        $this->setExpectedException(
            LogicException::class,
            'The flag-set Icecave\Flip\TestFlags does not have a flag named "unknown".'
        );

        $flags = TestFlags::defaults();
        $flags->unknown;
    }

    public function testSetProperty()
    {
        $this->setExpectedException(
            LogicException::class,
            'Flag-sets are immutable.'
        );

        $flags = TestFlags::defaults();
        $flags->unknown = true;
    }

    public function testFlagSetWithNonPrivateProperty()
    {
        $this->setExpectedException(
            LogicException::class,
            'The flag-set Icecave\Flip\TestFlagsWithNonPrivateProperty declares non-private property "foo". All properties must be private with boolean values.'
        );

        TestFlagsWithNonPrivateProperty::defaults();
    }

    public function testFlagSetWithNonBooleanProperty()
    {
        $this->setExpectedException(
            LogicException::class,
            'The flag-set Icecave\Flip\TestFlagsWithNonBooleanProperty declares non-boolean property "foo". All properties must be private with boolean values.'
        );

        TestFlagsWithNonBooleanProperty::defaults();
    }

    public function testFlagWithSameNameAsMethod()
    {
        $this->setExpectedException(
            LogicException::class,
            'The flag-set Icecave\Flip\TestFlagsWithSameNameAsMethod declares property with reserved name "defaults".'
        );

        TestFlagsWithSameNameAsMethod::defaults();
    }

    public function testToString()
    {
        $this->assertSame(
            '[foo, baz]',
            strval(TestFlags::defaults())
        );
    }
}
