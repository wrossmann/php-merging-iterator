# MergingIterator

Takes an array of iterators and a comparator function and iterates all elements in-order.

## Installation

```
composer require wrossmann\merging-iterator
```

## Usage

```
<?php
require('vendor/autoload.php');
use wrossmann\MergingIterator\MergingIterator;

function generate($input) {
    foreach( $input as $item ) {
        yield $item;
    }
}

$iterators = [
    generate([0,3,6]),
    generate([1,4,7]),
    generate([2,5,8])
];

$mi = new MergingIterator($iterators, function($a, $b){return $a-$b;});;

foreach($mi as $key => $item) {
    printf("%d\n", $item);
}
```

## Notes and Assumptions

* Assumes that the provided iterators themselves are in sorted order.
* Assumes that the provided comparator would sort the elements in the same order.
* Does not perform any actual sorting, only using the comparator to choose the appropriate value off the tips of the Iterators.

