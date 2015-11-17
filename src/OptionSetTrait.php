<?php
namespace Icecave\Flip;

use LogicException;
use ReflectionClass;

/**
 * Option-sets are immutable value objects that contain a pre-defined set of
 * boolean properties.
 */
trait OptionSetTrait
{
    /**
     * Create an option-set with default values.
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
     * Create an option-set with all options set to true.
     *
     * @return self
     */
    public static function all()
    {
        if (null === self::$__all) {
            $options = new self();

            foreach (self::$__options as $option) {
                $options->{$option} = true;
            }

            self::$__all = $options;
        }

        return self::$__all;
    }

    /**
     * Create an option-set with all options set to false.
     *
     * @return self
     */
    public static function none()
    {
        if (null === self::$__none) {
            $options = new self();

            foreach (self::$__options as $option) {
                $options->{$option} = false;
            }

            self::$__none = $options;
        }

        return self::$__none;
    }

    /**
     * Create an option-set by changing a specific option in the default option-set.
     *
     * @param string         $name      The option name.
     * @param tuple<boolean> $arguments A 1-tuple containing the option value.
     *
     * @return self
     */
    public static function __callStatic($name, array $arguments)
    {
        $options = new self();

        if (!isset(self::$__options[$name])) {
            throw new LogicException(
                sprintf(
                    'The option-set %s does not have an option named "%s".',
                    static::class,
                    $name
                )
            );
        }

        $options->{$name} = isset($arguments[0]) && $arguments[0];

        return $options;
    }

    /**
     * Create an option-set by changing a specific option in this option-set.
     *
     * @param string         $name      The option name.
     * @param tuple<boolean> $arguments A 1-tuple containing the option value.
     *
     * @return self
     */
    public function __call($name, array $arguments)
    {
        if (!isset(self::$__options[$name])) {
            throw new LogicException(
                sprintf(
                    'The option-set %s does not have an option named "%s".',
                    get_class($this),
                    $name
                )
            );
        }

        $options = clone $this;
        $options->{$name} = isset($arguments[0]) && $arguments[0];

        return $options;
    }

    /**
     * Get the value of an option.
     *
     * @param string $name The option name.
     *
     * @return boolean        The option value.
     * @throws LogicException if the option does not exist.
     */
    public function __get($name)
    {
        if (!isset(self::$__options[$name])) {
            throw new LogicException(
                sprintf(
                    'The option-set %s does not have an option named "%s".',
                    get_class($this),
                    $name
                )
            );
        }

        return $this->{$name};
    }

    /**
     * Get a string representation of the option set.
     */
    public function __toString()
    {
        $parts = [];

        foreach (get_object_vars($this) as $option => $value) {
            if ($value) {
                $parts[] = $option;
            }
        }

        return '[' . implode(', ', $parts) . ']';
    }

    /**
     * @access private
     *
     * Prevent setting of arbitrary options.
     *
     * @param string  $name  The option name.
     * @param boolean $value The option value.
     *
     * @throws LogicException under all circumstances.
     */
    public function __set($name, $value)
    {
        throw new LogicException('Option-sets are immutable.');
    }

    private function __construct()
    {
        if (null === self::$__options) {
            $reflector = new ReflectionClass($this);
            $options = [];

            foreach ($reflector->getProperties() as $property) {
                $property->setAccessible(true);

                if ($property->isStatic()) {
                    continue;
                } elseif (!$property->isPrivate()) {
                    throw new LogicException(
                        sprintf(
                            'The option-set %s declares non-private property "%s". All properties must be private with boolean values.',
                            get_class($this),
                            $property->getName()
                        )
                    );
                } elseif (!is_bool($property->getValue($this))) {
                    throw new LogicException(
                        sprintf(
                            'The option-set %s declares non-boolean property "%s". All properties must be private with boolean values.',
                            get_class($this),
                            $property->getName()
                        )
                    );
                } elseif ($reflector->hasMethod($property->getName())) {
                    throw new LogicException(
                        sprintf(
                            'The option-set %s declares property with reserved name "%s".',
                            get_class($this),
                            $property->getName()
                        )
                    );
                }

                $options[$property->getName()] = $property->getName();
            }

            self::$__options = $options;
        }
    }

    private static $__options;
    private static $__defaults;
    private static $__none;
    private static $__all;
}
