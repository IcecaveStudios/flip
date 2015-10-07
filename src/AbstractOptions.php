<?php
namespace Icecave\Flip;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * A set of boolean options.
 */
abstract class AbstractOptions extends AbstractEnumeration
{
    /**
     * Build an OptionCollection from a map of option value to boolean state.
     *
     * @param array<integer|string, boolean>     $options  A map of option value to boolean state.
     * @param array<integer|string, boolean|null $defaults A set of defaults to use instead of the ones defined in the enumeration class.
     *
     * @return OptionCollection
     */
    public static function build(array $options, array $defaults = null)
    {
        $result = OptionCollection::create(get_called_class());

        // Use the defaults defined in the enumeration class ...
        if (null === $defaults) {
            $defaults = static::defaults();
        }

        // Push defaults into the result ...
        foreach ($defaults as $value => $state) {
            $member = static::memberByValue($value);
            $result = $result->set($member, $state);
        }

        // Override defaults with provided options ...
        foreach ($options as $value => $state) {
            $member = static::memberByValue($value);
            $result = $result->set($member, $state);
        }

        return $result;
    }

    /**
     * Get the default values for the options in this set.
     *
     * This method may be overridden by the enumeration implementation.
     *
     * @return array<integer|string, boolean> A map of option value to boolean state.
     */
    public static function defaults()
    {
        return [];
    }
}
