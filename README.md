Jasny immutable
===

[![Build Status](https://travis-ci.org/jasny/immutable.svg?branch=master)](https://travis-ci.org/jasny/immutable)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/immutable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/immutable/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/immutable/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/immutable/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/immutable.svg)](https://packagist.org/packages/jasny/immutable)
[![Packagist License](https://img.shields.io/packagist/l/jasny/immutable.svg)](https://packagist.org/packages/jasny/immutable)

The library provides a helper methods for immutable objects.

Installation
---

    composer require jasny/immutable

Usage
---

### NoDynamicProperties

The `Jasny\Immutable\NoDynamicProperties` defines the `__set` method, which will throw a `LogicException` when
attempting to set a non-existing property.

```php
class ImmutableFoo
{
    use Jasny\Immutable\NoDynamicProperties;
}
```

### With

Immutable objects may have `with...` methods, that create an altered version of the object, while the immutable object
itself remains unchanged. An example are classes work as described in [PSR-7](https://www.php-fig.org/psr/psr-7/).

The `with...` methods in these objects typically follow the same method.

```php
class ImmutableFoo
{
    public function withSomething(string $newValue)
    {
        if ($this->something === $newValue) {
            return $this;
        }

        $clone = clone $this;
        $clone->something = $newValue;

        return $clone;
    }
}
```

The `Jasny\Immutable\With` trait implements a protected `withProperty()` and `withoutProperty()` method for setting and
unsetting properties on a clone of the object.

```php
class ImmutableFoo
{
    use Jasny\Immutable\With;

    public function withSomething(string $value): self
    {
        return $this->withProperty('something', $value);
    }

    public function withoutSomething(): self
    {
        return $this->withoutProperty('something');
    }
}
```

The trait contains the `withPropertyKey()` and `withoutPropertyKey()` methods for setting and unsetting an item of an
associative array.

```php
class ImmutableFoo
{
    use Jasny\Immutable\With;

    public function withColor(string $color, int $level): self
    {
        return $this->withPropertyKey('colors', $color, $level);
    }

    public function withoutColor(string $color): self
    {
        return $this->withoutPropertyKey('colors', $color);
    }
}
```

The `withPropertyItem()` and `withoutPropertyItem()` methods work on a sequential array to add and remove an item.

```php
class ImmutableFoo
{
    use Jasny\Immutable\With;

    public function withAddedService($service): self
    {
        return $this->withPropertyItem('services', $service, true /* unique */);
    }

    public function withoutService($service): self
    {
        return $this->withoutPropertyItem('services', $service);
    }
}
```

If the third argument of `withPropertyItem()` is to `true`, the array is considered to be a set and the item isn't added
if it's already in the array.

If the item is in the array multiple times, `withoutPropertyItem()` will remove them all. Strict comparison is used to
find items.
