## Fast Priority Queue implementation

[![Build Status](https://secure.travis-ci.org/ezimuel/FastPriorityQueue.svg?branch=master)](https://secure.travis-ci.org/ezimuel/FastPriorityQueue)

This is an efficient implementation of the **proprity queue** data structure in
pure PHP. PHP offers the [SplPriorityQueue](http://php.net/manual/en/class.splpriorityqueue.php)
class that implements a proprity queue, but this component has a "strange"
behaviour, see PHP request [#60926](https://bugs.php.net/bug.php?id=60926)
and PHP bug [#53710](https://bugs.php.net/bug.php?id=53710).
Basically, elements with the same priority are extracted in arbitrary order and
this can be a problem in many uses cases. Even because, the [definition](https://en.wikipedia.org/wiki/Priority_queue)
of Priority Queue says:

> In a priority queue, an element with high priority is served before an
> element with low priority. If two elements have the same priority, they
> are served according to their order in the queue.

Read the post [Taming SplPriorityQueue](https://mwop.net/blog/253-Taming-SplPriorityQueue.html)
by [Matthew Weier O'Phinney](https://github.com/weierophinney) for more
information about this SplPriorityQueue issue.

## Implementation details

I did not use the usual approach to implement the priority queue with an [heap](https://en.wikipedia.org/wiki/Heap_%28data_structure%29).
I used [ordered arrays](https://github.com/ezimuel/FastPriorityQueue/blob/master/src/PriorityQueue.php#L19)
grouped by [priorities](https://github.com/ezimuel/FastPriorityQueue/blob/master/src/PriorityQueue.php#L26).
For the array iteration I used the [next()](http://php.net/manual/en/function.next.php),
and [current()](http://php.net/manual/en/function.current.php) functions of PHP.

To get the priorities in order, I use the [max()](http://php.net/manual/en/function.max.php)
function of PHP. To retrieve the next priority, I remove the previous one from
the array, using [unset()](http://php.net/manual/en/function.unset.php), and I
re-apply the max() function to the remaining values.

This solution is very simple and offers very good performance (see the benchmark
below).

## Benchmark

I provided a simple script to test the performance of the implementation. You
can execute this test running the following command:

```
php benchmark/test.php
```

This script executes 50'000 insert and extract operations using different
priority queue implementations:

- [SplPriorityQueue](http://php.net/manual/en/class.splpriorityqueue.php)
- [Zend\Stdlib\SplPriorityQueue](https://github.com/zendframework/zend-stdlib/blob/master/src/SplPriorityQueue.php)
- [Zend\Stdlib\PriorityQueue](https://github.com/zendframework/zend-stdlib/blob/master/src/PriorityQueue.php)

I included also the SplPriorityQueue of PHP to have a starter point for the
comparisation, even if the results of this component are not correct, as
commented above.

I executed the benchmark using an Intel Core i5-2500 at 3.30GHz with 8 Gb of RAM
running Ubuntu Linux 14.04 and PHP 5.5.9. Here the results:

```
--- Benchmark 50,000 insert and extract with a fixed priority
SplPriorityQueue                : 0.05173206 (sec)
FastPriorityQueue\PriorityQueue : 0.07072687 (sec)
Zend\Stdlib\SplPriorityQueue    : 0.23528290 (sec)
Zend\Stdlib\PriorityQueue       : 0.39357114 (sec)

--- Benchmark 50,000 insert and extract with random priority (1,100)
SplPriorityQueue                : 0.06713820 (sec)
FastPriorityQueue\PriorityQueue : 0.10090804 (sec)
Zend\Stdlib\SplPriorityQueue    : 0.44940901 (sec)
Zend\Stdlib\PriorityQueue       : 0.65850401 (sec)
```

As you can see the execution time of FastPriorityQueue is very good, from **3x
to 6x faster** than the others implementation (except the SplPriorityQueue that
is out of the comparison).

## Unit Tests

You can run the unit tests running the following commmand, after the installation
using [composer](https://getcomposer.org/).

```
vendor/bin/phpunit
```

## Copyright

This work is licensed under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/).

(C) Copyright 2015 by [Enrico Zimuel](http://www.zimuel.it).
