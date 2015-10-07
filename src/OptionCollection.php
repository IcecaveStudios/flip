<?php
namespace Icecave\Flip;

use ArrayAccess;
use Eloquent\Enumeration\EnumerationInterface;
use InvalidArgumentException;
use Iterator;
use IteratorAggregate;
use LogicException;

/**
 * A map of enumeration member to boolean state.
 *
 * Option collections are immutable, setting a value creates and returns a new
 * collection instance.
 */
final class OptionCollection implements ArrayAccess, IteratorAggregate
{
    /**
     * Create an option collection.
     *
     * @param string $type The enumeration type name (must implement EnumerationInterface)
     *
     * @return OptionCollection
     */
    public static function create($type)
    {
        return new self($type);
    }

    /**
     * Get the boolean state of an enumeration member.
     *
     * @param EnumerationInterface $member The enumeration member.
     *
     * @return boolean                  The boolean state for this member.
     * @throws InvalidArgumentException if $member is not an instance of the type given when the collection was created.
     */
    public function get(EnumerationInterface $member)
    {
        if (!$member instanceof $this->type) {
            throw new InvalidArgumentException(
                'Expected a member of the "' . $this->type . '" enumeration.'
            );
        }

        return isset(
            $this->options[$member->key()]
        );
    }

    /**
     * Set the boolean state of an enumeration member.
     *
     * Option collections are immutable, setting a value creates and returns a new
     * collection instance.
     *
     * @param EnumerationInterface $member The enumeration member.
     * @param boolean              $state  The boolean state for this member.
     *
     * @return OptionCollection         The modified option collection.
     * @throws InvalidArgumentException if $member is not an instance of the type given when the collection was created.
     */
    public function set(EnumerationInterface $member, $state)
    {
        if (!$member instanceof $this->type) {
            throw new InvalidArgumentException(
                'Expected a member of the "' . $this->type . '" enumeration.'
            );
        }

        $result = clone $this;

        if ($state) {
            $result->options[$member->key()] = true;
        } else {
            unset($result->options[$member->key()]);
        }

        return $result;
    }

    /**
     * Iterate over the enumeration members.
     *
     * @return Iterator<EnumerationInterface, boolean> An iterator where keys are the enumeration members and values are the boolean state.
     */
    public function getIterator()
    {
        $members = call_user_func($this->type . '::members');

        foreach ($members as $member) {
            yield $member => isset($this->options[$member->key()]);
        }
    }

    /**
     * Check if the given enumeration member exists in this collection.
     *
     * This method will return true for all members of the enumeration type
     * given when the collection was created.
     *
     * @param mixed $offset The offset to check.
     *
     * @return boolean True if the offset exists, that is $this[$offset] will execute successfully.
     */
    public function offsetExists($offset)
    {
        return $offset instanceof $this->type;
    }

    /**
     * Get the boolean state of an enumeration member.
     *
     * @param EnumerationInterface $member The enumeration member.
     *
     * @return boolean                  The boolean state for this member.
     * @throws InvalidArgumentException if $member is not an instance of the type given when the collection was created.
     */
    public function offsetGet($member)
    {
        return $this->get($member);
    }

    /**
     * @access private
     */
    public function offsetSet($offset, $value)
    {
        throw new LogicException('Option collections are immutable.');
    }

    /**
     * @access private
     */
    public function offsetUnset($offset)
    {
        throw new LogicException('Option collections are immutable.');
    }

    /**
     * @param string $type The enumeration type name (must implement EnumerationInterface)
     */
    private function __construct($type)
    {
        if (!is_subclass_of($type, EnumerationInterface::class)) {
            throw new InvalidArgumentException(
                'Type must be an enumeration.'
            );
        }

        $this->type = $type;
        $this->options = [];
    }

    private $type;
    private $options;
}
