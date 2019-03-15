Jasny immutable
===

[![Build Status](https://travis-ci.org/jasny/immutable.svg?branch=master)](https://travis-ci.org/jasny/immutable)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/immutable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/immutable/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/immutable/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/immutable/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/immutable.svg)](https://packagist.org/packages/jasny/immutable)
[![Packagist License](https://img.shields.io/packagist/l/jasny/immutable.svg)](https://packagist.org/packages/jasny/immutable)

The library provides a trait with a helper method for immutable object.

Immutable objects may have `with...` methods, that create an altered version of the object, while the immulable
object itself remains unchanged. An example are classes work as described in PSR-7.

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


Installation
---

    composer require jasny/immutable

Usage
---

The library contains a single trait `Jasny\Immutable\With`, which implements the protecteed `with()` method. This
method takes the property name as string and the new value as parameters.

```php
class ImmutableFoo
{
    use Jasny\Immutable\With;

    public function withSomething(string $newValue)
    {
        return $this->with('something', $newValue);
    }
}
```

