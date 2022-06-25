<?php

namespace wrossmann\MergingIterator;

class MergingIterator implements \Iterator {
    protected $iterators = [];
    protected $comparator;
    protected $cur_index = NULL;
    
    public function __construct( array $iterators, callable $comparator ) {
        foreach( $iterators as $iterator ) {
            $this->addIterator($iterator);
        }
        $this->comparator = $comparator;
    }
    
    protected function addIterator( \Iterator $iterator ) {
        $this->iterators[] = $iterator;
    }
    
    protected function getIndexOfCurrent() {
        if( ! $this->valid() ) {
            throw new Exception('Cannot get current() in invalid iterators.');
        }
        if( is_null($this->cur_index) ) {
            $index = -1;
            $value = NULL;
            foreach( $this->iterators as $cur_index => $iterator ) {
                if( ! $iterator->valid() ) { continue; }
                $cur = $iterator->current();
                if( $index == -1 || ($this->comparator)($cur, $value) < 0 ) {
                    $index = $cur_index;
                    $value = $cur;
                }
            }
            $this->cur_index = $index;
        }
        return $this->cur_index;
    }
    
    // Iterator interface functions
    // ReturnTypeWillChange attribute used for :mixed compatibility across 7/8

    #[\ReturnTypeWillChange]
    public function current() {
        return $this->iterators[$this->getIndexOfCurrent()]->current();
    }
    
    #[\ReturnTypeWillChange]
    public function key() {
        return $this->iterators[$this->getIndexOfCurrent()]->key();
    }
    
    public function next() :void {
        $this->iterators[$this->getIndexOfCurrent()]->next();
        $this->cur_index = NULL;
    }
    
    public function rewind() :void {
        array_walk($this->iterators, function($i){$i->rewind();});
        $this->cur_index = NULL;
    }
    
    public function valid(): bool {
        return ! empty(array_filter(array_map(function($i){return $i->valid();}, $this->iterators)));
    }
}

