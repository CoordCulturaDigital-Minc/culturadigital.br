# stash [![Build Status](https://secure.travis-ci.org/ehough/stash.png)](http://travis-ci.org/ehough/stash)

Fork of [tedivm/Stash](https://github.com/tedivm/Stash) compatible with PHP 5.2+.

### Motivation

`tedivm/Stash` is a fantastic caching library, but it's only compatible with PHP 5.3+. While 97% of PHP servers run PHP 5.2 or higher,
a whopping **47% of all servers are still running PHP 5.2** ([source](http://w3techs.com/technologies/details/pl-php/5/all)).
It would be a shame to exempt this library from nearly half of the world's servers just because of a few version incompatibilities.

Once PHP 5.3+ adoption levels near closer to 100%, this library will be retired.

### Differences from [tedivm/Stash](https://github.com/tedivm/Stash)

The primary difference is naming conventions of the `tedivm/stash` classes.
Instead of the `\Stash` namespace (and sub-namespaces), prefix the `tedivm/Stash` class names
with `ehough_stash` and follow the [PEAR naming convention](http://pear.php.net/manual/en/standards.php)

A few examples of class naming conversions:

    \Stash\Pool              ----->    ehough_stash_Pool
    \Stash\Driver\Memcache   ----->    ehough_stash_driver_Memcache

### Usage

Visit [stash.tedivm.com](http://stash.tedivm.com) for the current documentation.
