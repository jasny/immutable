<?php

declare(strict_types=1);

namespace Jasny\Immutable;

/**
 * Trait with the `withProperty` methods that can be used by classes of immutable objects.
 *
 * {@internal Some lines are expected to be covered, which should be ignored. Added codeCoverageIgnore. }}
 */
trait With
{
    /**
     * Return a copy with a changed property.
     * Returns this object if the resulting object would be the same as the current one.
     *
     * @param string  $property
     * @param mixed   $value
     * @return static
     * @throws \BadMethodCallException if property doesn't exist
     */
    private function withProperty(string $property, $value)
    {
        if (!property_exists($this, $property)) {
            throw new \BadMethodCallException(sprintf('%s has no property "%s"', get_class($this), $property));
        }

        if (
            (isset($this->{$property}) && $this->{$property} === $value) || // Typed property may be not initialized
            (!isset($this->{$property}) && $value === null)        
        ) {
            return $this;
        }

        $clone = clone $this;
        $clone->{$property} = $value;

        return $clone;
    }

    /**
     * Return a copy with a property unset.
     * Returns this object if the resulting object would be the same as the current one.
     *
     * @param string  $property
     * @return static
     * @throws \BadMethodCallException if property doesn't exist
     */
    private function withoutProperty(string $property)
    {
        if (!property_exists($this, $property)) {
            throw new \BadMethodCallException(sprintf('%s has no property "%s"', get_class($this), $property));
        }

        if (!isset($this->{$property})) {
            return $this;
        }

        $clone = clone $this;
        unset($clone->{$property});

        return $clone;
    }


    /**
     * Return a copy with an added item for a property.
     * Returns this object if the resulting object would be the same as the current one.
     *
     * @param string  $property
     * @param string  $key
     * @param mixed   $value
     * @return static
     * @throws \BadMethodCallException if property doesn't exist
     */
    private function withPropertyKey(string $property, string $key, $value)
    {
        if (!property_exists($this, $property)) {
            throw new \BadMethodCallException(sprintf('%s has no property "%s"', get_class($this), $property));
        } // @codeCoverageIgnore
        if (!is_array($this->{$property}) && !$this->{$property} instanceof \ArrayAccess) {
            throw new \BadMethodCallException(sprintf('%s::$%s is not an array', get_class($this), $property));
        }

        if (isset($this->{$property}[$key]) && $this->{$property}[$key] === $value) {
            return $this;
        }

        $clone = clone $this;
        $clone->{$property}[$key] = $value;

        return $clone;
    }

    /**
     * Return a copy with a removed item from a property.
     * Returns this object if the resulting object would be the same as the current one.
     *
     * @param string  $property
     * @param string  $key
     * @return static
     * @throws \BadMethodCallException if property doesn't exist or isn't an array
     */
    private function withoutPropertyKey(string $property, string $key)
    {
        if (!property_exists($this, $property)) {
            throw new \BadMethodCallException(sprintf('%s has no property "%s"', get_class($this), $property));
        } // @codeCoverageIgnore
        if (!is_array($this->{$property}) && !$this->{$property} instanceof \ArrayAccess) {
            throw new \BadMethodCallException(sprintf('%s::$%s is not an array', get_class($this), $property));
        }

        if (!isset($this->{$property}[$key])) {
            return $this;
        }

        $clone = clone $this;
        unset($clone->{$property}[$key]);

        return $clone;
    }


    /**
     * Return a copy with a value added to a sequential array.
     *
     * @param string  $property
     * @param mixed   $value
     * @param mixed   $unique    Don't add if the array already has a copy of the value.
     * @return static
     * @throws \BadMethodCallException if property doesn't exist or isn't an array
     */
    private function withPropertyItem(string $property, $value, bool $unique = false)
    {
        if (!property_exists($this, $property)) {
            throw new \BadMethodCallException(sprintf('%s has no property "%s"', get_class($this), $property));
        }
        if (!is_array($this->{$property})) {
            throw new \BadMethodCallException(sprintf('%s::$%s is not an array', get_class($this), $property));
        }

        if ($unique && in_array($value, $this->{$property}, true)) {
            return $this;
        }

        $clone = clone $this;
        $clone->{$property}[] = $value;

        return $clone;
    }

    /**
     * Return a copy with a value removed from a sequential array.
     * Returns this object if the resulting object would be the same as the current one.
     *
     * @param string  $property
     * @param mixed   $value
     * @return static
     * @throws \BadMethodCallException if property doesn't exist or isn't an array
     */
    private function withoutPropertyItem(string $property, $value)
    {
        if (!property_exists($this, $property)) {
            throw new \BadMethodCallException(sprintf('%s has no property "%s"', get_class($this), $property));
        }
        if (!is_array($this->{$property})) {
            throw new \BadMethodCallException(sprintf('%s::$%s is not an array', get_class($this), $property));
        }

        $keys = array_keys($this->{$property}, $value, true);

        if ($keys === []) {
            return $this;
        }

        $clone = clone $this;
        $clone->{$property} = array_values(array_diff_key($clone->{$property}, array_fill_keys($keys, null)));

        return $clone;
    }
}
