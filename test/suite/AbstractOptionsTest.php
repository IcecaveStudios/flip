<?php
namespace Icecave\Flip;

use PHPUnit_Framework_TestCase;

class AbstractOptionsTest extends PHPUnit_Framework_TestCase
{
    public function testBuildWithClassDefaults()
    {
        $expected = OptionCollection::create(TestOptions::class)
            ->set(TestOptions::FOO(), true);

        $this->assertEquals(
            $expected,
            TestOptions::build(
                []
            )
        );
    }

    public function testBuildCanOverrideClassDefaults()
    {
        $expected = OptionCollection::create(TestOptions::class)
            ->set(TestOptions::BAZ(), true)
            ->set(TestOptions::QUX(), true);

        $this->assertEquals(
            $expected,
            TestOptions::build(
                [
                    TestOptions::FOO => false,
                    TestOptions::BAZ => true,
                    TestOptions::QUX => true,
                ]
            )
        );
    }

    public function testBuildWithCustomDefaults()
    {
        $expected = OptionCollection::create(TestOptions::class)
            ->set(TestOptions::BAR(), true);

        $this->assertEquals(
            $expected,
            TestOptions::build(
                [],
                [TestOptions::BAR => true]
            )
        );
    }

    public function testBuildCanOverrideCustomDefaults()
    {
        $expected = OptionCollection::create(TestOptions::class)
            ->set(TestOptions::FOO(), true);

        $this->assertEquals(
            $expected,
            TestOptions::build(
                [
                    TestOptions::FOO => true,
                    TestOptions::BAR => false,
                ],
                [TestOptions::BAR => true]
            )
        );
    }

    public function testDefaults()
    {
        $this->assertSame(
            [],
            AbstractOptions::defaults()
        );
    }
}
