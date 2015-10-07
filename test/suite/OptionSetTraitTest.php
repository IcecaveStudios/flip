<?php
namespace Icecave\Flip;

use LogicException;
use PHPUnit_Framework_TestCase;

class OptionSetTraitTest extends PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $options = TestOptions::defaults();

        $this->assertTrue($options->foo);
        $this->assertFalse($options->bar);
        $this->assertTrue($options->baz);
        $this->assertFalse($options->qux);
    }

    public function testAll()
    {
        $options = TestOptions::all();

        $this->assertTrue($options->foo);
        $this->assertTrue($options->bar);
        $this->assertTrue($options->baz);
        $this->assertTrue($options->qux);
    }

    public function testNone()
    {
        $options = TestOptions::none();

        $this->assertFalse($options->foo);
        $this->assertFalse($options->bar);
        $this->assertFalse($options->baz);
        $this->assertFalse($options->qux);
    }

    public function testFluent()
    {
        $options = TestOptions::bar(true);

        // defaults + bar ...
        $this->assertTrue($options->foo);
        $this->assertTrue($options->bar);
        $this->assertTrue($options->baz);
        $this->assertFalse($options->qux);

        // change a single option ...
        $new = $options->foo(false);
        $this->assertFalse($new->foo);
        $this->assertTrue($new->bar);
        $this->assertTrue($new->baz);
        $this->assertFalse($new->qux);

        // original is unchanged ...
        $this->assertTrue($options->foo);
        $this->assertTrue($options->bar);
        $this->assertTrue($options->baz);
        $this->assertFalse($options->qux);
    }

    public function testSetUnknownOptionViaCallStatic()
    {
        $this->setExpectedException(
            LogicException::class,
            'The option-set Icecave\Flip\TestOptions does not have an option named "unknown".'
        );

        TestOptions::unknown(true);
    }

    public function testSetUnknownOptionViaCall()
    {
        $this->setExpectedException(
            LogicException::class,
            'The option-set Icecave\Flip\TestOptions does not have an option named "unknown".'
        );

        TestOptions::defaults()->unknown(true);
    }

    public function testGetUnknownOption()
    {
        $this->setExpectedException(
            LogicException::class,
            'The option-set Icecave\Flip\TestOptions does not have an option named "unknown".'
        );

        $options = TestOptions::defaults();
        $options->unknown;
    }

    public function testSetProperty()
    {
        $this->setExpectedException(
            LogicException::class,
            'Option-sets are immutable.'
        );

        $options = TestOptions::defaults();
        $options->unknown = true;
    }

    public function testOptionSetWithNonPrivateProperty()
    {
        $this->setExpectedException(
            LogicException::class,
            'The option-set Icecave\Flip\TestOptionsWithNonPrivateProperty declares non-private property "foo". All properties must be private with boolean values.'
        );

        TestOptionsWithNonPrivateProperty::defaults();
    }

    public function testOptionSetWithNonBooleanProperty()
    {
        $this->setExpectedException(
            LogicException::class,
            'The option-set Icecave\Flip\TestOptionsWithNonBooleanProperty declares non-boolean property "foo". All properties must be private with boolean values.'
        );

        TestOptionsWithNonBooleanProperty::defaults();
    }

    public function testOptionWithSameNameAsMethod()
    {
        $this->setExpectedException(
            LogicException::class,
            'The option-set Icecave\Flip\TestOptionsWithSameNameAsMethod declares property with reserved name "defaults".'
        );

        TestOptionsWithSameNameAsMethod::defaults();
    }
}
