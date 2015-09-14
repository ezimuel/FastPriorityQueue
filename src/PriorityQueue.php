<?php

/**
 * Fast integer priority queue implementation using ordered arrays.
 *
 * @author Enrico Zimuel (enrico@zimuel.it)
 */

namespace FastPriorityQueue;

use Countable;
use Iterator;

class PriorityQueue implements Iterator, Countable
{
    protected $values = [];
    protected $priorities = [];
    protected $max = 0;
    protected $tot = 0;
    protected $index = 0;

    public function insert($value, $priority)
    {
        if (!is_int($priority) || $priority < 1) {
            throw new InvalidArgumentException('The priority must be a positive integer');
        }
        $this->values[$priority][] = $value;
        if (!isset($this->priorities[$priority])) {
            $this->priorities[$priority] = $priority;
            if ($priority > $this->max) {
                $this->max = $priority;
            }
        }
        $this->tot++;
    }

    public function extract()
    {
        if (!$this->valid()) {
            return false;
        }
        $value = $this->current();
        $this->next();

        return $value;
    }

    public function count()
    {
        return $this->tot;
    }

    public function current()
    {
        return current($this->values[$this->max]);
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        if (false === next($this->values[$this->max])) {
            unset($this->priorities[$this->max]);
            unset($this->values[$this->max]);
            $this->max = empty($this->priorities) ? 0 : max($this->priorities);
        }
        $this->index++;
        $this->tot--;
    }

    public function valid()
    {
        return isset($this->values[$this->max]);
    }

    public function rewind()
    {
        // No operation here, never moves from the top of the priority queue
    }
}
