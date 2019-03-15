<?php declare(strict_types=1);

namespace Jasny\Immutable;

/**
 * Trait with the `with` method that can be used by classes of immutable objects.
 */
trait With
{
    /**
     * Return a copy with a changed property.
     * Returns this object if the resulting object would be the same as the current one.
     *
     * @param string  $property
     * @param mised   $gateway
     * @return self
     */
    protected function with(string $property, $newValue)
    {
        if (!property_exists($this, $property)) {
            throw new \BadMethodCallException(sprintf('%s has no property "%s"', get_class($this), $property));
        }

        if ($this->$property === $newValue) {
            return $this;
        }

        $clone = clone $this;
        $clone->$property = $newValue;

        return $clone;
    }
}
