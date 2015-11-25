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

    public function testDiff()
    {
        $defaults = TestFlags::defaults();

        // diff to same
        $flags = $defaults->diff(
            TestFlags::defaults()
        );
        $this->assertFalse($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertFalse($flags->baz);
        $this->assertFalse($flags->qux);

        // diff to all
        $flags = $defaults->diff(
            TestFlags::all()
        );
        $this->assertFalse($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertFalse($flags->baz);
        $this->assertFalse($flags->qux);

        // diff to none
        $flags = $defaults->diff(
            TestFlags::none()
        );
        $this->assertTrue($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);
    }

    public function testSymmetricDiff()
    {
        $defaults = TestFlags::defaults();

        // symmetric diff to same
        $flags = $defaults->symmetricDiff(
            TestFlags::defaults()
        );
        $this->assertFalse($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertFalse($flags->baz);
        $this->assertFalse($flags->qux);

        // symmetric diff to all
        $flags = $defaults->symmetricDiff(
            TestFlags::all()
        );
        $this->assertFalse($flags->foo);
        $this->assertTrue($flags->bar);
        $this->assertFalse($flags->baz);
        $this->assertTrue($flags->qux);

        // symmetric diff to none
        $flags = $defaults->symmetricDiff(
            TestFlags::none()
        );
        $this->assertTrue($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);
    }

    public function testIntersect()
    {
        $defaults = TestFlags::defaults();

        // intersect to same
        $flags = $defaults->intersect(
            TestFlags::defaults()
        );
        $this->assertTrue($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);

        // intersect to all
        $flags = $defaults->intersect(
            TestFlags::all()
        );
        $this->assertTrue($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);

        // intersect to none
        $flags = $defaults->intersect(
            TestFlags::none()
        );
        $this->assertFalse($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertFalse($flags->baz);
        $this->assertFalse($flags->qux);
    }

    public function testUnion()
    {
        $defaults = TestFlags::defaults();

        // union to same
        $flags = $defaults->union(
            TestFlags::defaults()
        );
        $this->assertTrue($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);

        // union to all
        $flags = $defaults->union(
            TestFlags::all()
        );
        $this->assertTrue($flags->foo);
        $this->assertTrue($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertTrue($flags->qux);

        // union to none
        $flags = $defaults->union(
            TestFlags::none()
        );
        $this->assertTrue($flags->foo);
        $this->assertFalse($flags->bar);
        $this->assertTrue($flags->baz);
        $this->assertFalse($flags->qux);
    }

    public function testInverse()
    {
        $flags = TestFlags::defaults()->inverse();

        $this->assertFalse($flags->foo);
        $this->assertTrue($flags->bar);
        $this->assertFalse($flags->baz);
        $this->assertTrue($flags->qux);
    }
}
