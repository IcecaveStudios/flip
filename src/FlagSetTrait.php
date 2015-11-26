<?php

namespace Icecave\Flip;

use LogicException;
use ReflectionClass;

/**
 * Flag-sets are immutable value objects that contain a pre-defined set of
 * boolean properties.
 */
trait FlagSetTrait
{
    /**
     * Create a flag-set with default values.
     *
     * @return self
     */
    public static function defaults()
    {
        if (null === self::$__defaults) {
            self::$__defaults = new self();
        }

        return self::$__defaults;
    }

    /**
     * Create a flag-set with all flags set to true.
     *
     * @return self
     */
    public static function all()
    {
        if (null === self::$__all) {
            $flags = new self();

            foreach (self::$__flags as $flag) {
                $flags->{$flag} = true;
            }

            self::$__all = $flags;
        }

        return self::$__all;
    }

    /**
     * Create a flag-set with all flags set to false.
     *
     * @return self
     */
    public static function none()
    {
        if (null === self::$__none) {
            $flags = new self();

            foreach (self::$__flags as $flag) {
                $flags->{$flag} = false;
            }

            self::$__none = $flags;
        }

        return self::$__none;
    }

    /**
     * Create a flag-set by changing a specific flag in the default flag-set.
     *
     * @param string         $name      The flag name.
     * @param tuple<boolean> $arguments A 1-tuple containing the flag value.
     *
     * @return self
     */
    public static function __callStatic($name, array $arguments)
    {
        $flags = new self();

        if (!isset(self::$__flags[$name])) {
            throw new LogicException(
                sprintf(
                    'The flag-set %s does not have a flag named "%s".',
                    static::class,
                    $name
                )
            );
        }

        $flags->{$name} = isset($arguments[0]) && $arguments[0];

        return $flags;
    }

    /**
     * Create a flag-set by changing a specific flag in this flag-set.
     *
     * @param string         $name      The flag name.
     * @param tuple<boolean> $arguments A 1-tuple containing the flag value.
     *
     * @return self
     */
    public function __call($name, array $arguments)
    {
        if (!isset(self::$__flags[$name])) {
            throw new LogicException(
                sprintf(
                    'The flag-set %s does not have a flag named "%s".',
                    get_class($this),
                    $name
                )
            );
        }

        $flags = clone $this;
        $flags->{$name} = isset($arguments[0]) && $arguments[0];

        return $flags;
    }

    /**
     * Get the value of a flag.
     *
     * @param string $name The flag name.
     *
     * @return boolean        The flag value.
     * @throws LogicException if the flag does not exist.
     */
    public function __get($name)
    {
        if (!isset(self::$__flags[$name])) {
            throw new LogicException(
                sprintf(
                    'The flag-set %s does not have a flag named "%s".',
                    get_class($this),
                    $name
                )
            );
        }

        return $this->{$name};
    }

    /**
     * Get a string representation of the flag set.
     *
     * @return string The string representation.
     */
    public function __toString()
    {
        $parts = [];

        foreach (get_object_vars($this) as $flag => $value) {
            if ($value) {
                $parts[] = $flag;
            }
        }

        return '[' . implode(', ', $parts) . ']';
    }

    /**
     * @access private
     *
     * Prevent setting of arbitrary flags.
     *
     * @param string  $name  The flag name.
     * @param boolean $value The flag value.
     *
     * @throws LogicException under all circumstances.
     */
    public function __set($name, $value)
    {
        throw new LogicException('Flag-sets are immutable.');
    }

    /**
     * Compute the difference of flag-sets.
     *
     * @param self $other The other flag set to compare to.
     *
     * @return self A flag-set of the flags in $this and not in $other.
     */
    public function diff(self $other)
    {
        $result = new self();
        foreach (self::$__flags as $flag) {
            $result->{$flag} = $this->{$flag} && !$other->{$flag};
        }

        return $result;
    }

    /**
     * Compute the symmetric difference of flag-sets.
     *
     * @param self $other The other flag set to compare to.
     *
     * @return self A flag-set of the flags in $this and not in $other, and vice versa.
     */
    public function symmetricDiff(self $other)
    {
        $result = new self();
        foreach (self::$__flags as $flag) {
            $result->{$flag} = $this->{$flag} !== $other->{$flag};
        }

        return $result;
    }

    /**
     * Compute the intersection of flag-sets.
     *
     * @param self $other The other flag set to intersect with.
     *
     * @return self A flag-set of the flags in $this and $other.
     */
    public function intersect(self $other)
    {
        $result = new self();
        foreach (self::$__flags as $flag) {
            $result->{$flag} = $this->{$flag} && $other->{$flag};
        }

        return $result;
    }

    /**
     * Compute the union of flag-sets.
     *
     * @param self $other The other flag set to union with.
     *
     * @return self A flag-set of the flags in $this or $other.
     */
    public function union(self $other)
    {
        $result = new self();
        foreach (self::$__flags as $flag) {
            $result->{$flag} = $this->{$flag} || $other->{$flag};
        }

        return $result;
    }

    /**
     * Compute the inverse of this flag set.
     *
     * @return self The inverse of the flags in this flag-set.
     */
    public function inverse()
    {
        $result = new self();
        foreach (self::$__flags as $flag) {
            $result->{$flag} = !$this->{$flag};
        }

        return $result;
    }

    private function __construct()
    {
        if (null === self::$__flags) {
            $reflector = new ReflectionClass($this);
            $flags = [];

            foreach ($reflector->getProperties() as $property) {
                $property->setAccessible(true);

                if ($property->isStatic()) {
                    continue;
                } elseif (!$property->isPrivate()) {
                    throw new LogicException(
                        sprintf(
                            'The flag-set %s declares non-private property "%s". All properties must be private with boolean values.',
                            get_class($this),
                            $property->getName()
                        )
                    );
                } elseif (!is_bool($property->getValue($this))) {
                    throw new LogicException(
                        sprintf(
                            'The flag-set %s declares non-boolean property "%s". All properties must be private with boolean values.',
                            get_class($this),
                            $property->getName()
                        )
                    );
                } elseif ($reflector->hasMethod($property->getName())) {
                    throw new LogicException(
                        sprintf(
                            'The flag-set %s declares property with reserved name "%s".',
                            get_class($this),
                            $property->getName()
                        )
                    );
                }

                $flags[$property->getName()] = $property->getName();
            }

            self::$__flags = $flags;
        }
    }

    private static $__flags;
    private static $__defaults;
    private static $__none;
    private static $__all;
}
