<?php
namespace Icecave\Flip;

use Eloquent\Enumeration\EnumerationInterface;
use Eloquent\Phony\Phpunit\Phony;
use InvalidArgumentException;
use LogicException;
use PHPUnit_Framework_TestCase;

class OptionCollectionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = OptionCollection::create(TestOptions::class);
    }

    public function testCreateWithNonEnumeration()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            'Type must be an enumeration.'
        );

        OptionCollection::create(__NAMESPACE__ . '\NotAnEnumeration');
    }

    public function testGet()
    {
        $this->assertFalse(
            $this->subject->get(TestOptions::FOO())
        );
    }

    public function testGetWithMemberFromOtherEnumeration()
    {
        $member = Phony::fullMock(EnumerationInterface::class)->mock();

        $this->setExpectedException(
            InvalidArgumentException::class,
            'Expected a member of the "' . TestOptions::class . '" enumeration.'
        );

        $this->subject->get($member);
    }

    public function testSet()
    {
        $subject = $this->subject->set(TestOptions::FOO(), true);

        $this->assertInstanceOf(
            OptionCollection::class,
            $subject
        );

        $this->assertTrue(
            $subject->get(TestOptions::FOO())
        );

        $subject = $this->subject->set(TestOptions::FOO(), false);

        $this->assertInstanceOf(
            OptionCollection::class,
            $subject
        );

        $this->assertFalse(
            $subject->get(TestOptions::FOO())
        );
    }

    public function testSetRetainsPreviousSetOptions()
    {
        $subject = $this->subject->set(TestOptions::FOO(), true);
        $subject = $subject->set(TestOptions::BAR(), true);

        $this->assertTrue(
            $subject->get(TestOptions::FOO())
        );
    }

    public function testSetDoesNotChangeOriginalObject()
    {
        $this->subject->set(TestOptions::FOO(), true);

        $this->assertFalse(
            $this->subject->get(TestOptions::FOO())
        );
    }

    public function testSetWithMemberFromOtherEnumeration()
    {
        $member = Phony::fullMock(EnumerationInterface::class)->mock();

        $this->setExpectedException(
            InvalidArgumentException::class,
            'Expected a member of the "' . TestOptions::class . '" enumeration.'
        );

        $this->subject->set($member, true);
    }

    public function testGetIterator()
    {
        $subject = $this->subject->set(TestOptions::FOO(), true);

        foreach ($subject as $key => $value) {
            $this->assertInstanceOf(
                TestOptions::class,
                $key
            );

            if ($key === TestOptions::FOO()) {
                $this->assertTrue($value);
            } else {
                $this->assertFalse($value);
            }
        }
    }

    public function testOffsetExists()
    {
        foreach (TestOptions::members() as $member) {
            $this->assertTrue(
                isset(
                    $this->subject[$member]
                )
            );
        }
    }

    public function testOffsetExistsWithNonEnumerationMember()
    {
        $this->assertFalse(
            isset(
                $this->subject['<non-enum>']
            )
        );
    }

    public function testOffsetExistsMemberFromOtherEnumeration()
    {
        $member = Phony::fullMock(EnumerationInterface::class)->mock();

        $this->assertFalse(
            isset(
                $this->subject[$member]
            )
        );
    }

    public function testGetOffset()
    {
        $subject = $this->subject->set(TestOptions::FOO(), true);

        $this->assertTrue(
            $subject[TestOptions::FOO()]
        );

        $this->assertFalse(
            $subject[TestOptions::BAR()]
        );
    }

    public function testGetOffsetWithMemberFromOtherEnumeration()
    {
        $member = Phony::fullMock(EnumerationInterface::class)->mock();

        $this->setExpectedException(
            InvalidArgumentException::class,
            'Expected a member of the "' . TestOptions::class . '" enumeration.'
        );

        $this->subject[$member];
    }

    public function testOffsetSet()
    {
        $this->setExpectedException(
            LogicException::class,
            'Option collections are immutable.'
        );

        $this->subject[TestOptions::FOO()] = true;
    }

    public function testOffsetUnset()
    {
        $this->setExpectedException(
            LogicException::class,
            'Option collections are immutable.'
        );

        unset($this->subject[TestOptions::FOO()]);
    }
}
