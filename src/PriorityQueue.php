<?php
namespace FastPriorityQueue;

/**
 * Fast integer priority queue implementation using ordered arrays
 *
 * @author Enrico Zimuel (enrico@zimuel.it)
 */
class PriorityQueue implements \Iterator, \Countable
{
    /**
     * Queue elements (sorted by priority).
     * 
     * @var array
     */
    protected $values = [];

    /**
     * Priorities.
     * 
     * @var array
     */
    protected $priorities = [];

    /**
     * Maximum priority contained.
     * 
     * @var int
     */
    protected $max = 0;

    /**
     * Total elements contained.
     * 
     * @var int
     */
    protected $tot = 0;

    /**
     * Current index while looping the queue.
     * 
     * @var int
     */
    protected $index = 0;

    /**
     * Insert a new element into the queue.
     * 
     * @param mixed $value    Element to insert.
     * @param int   $priority Priority from 1 to PHP_INT_MAX.
     */
    public function insert($value, $priority)
    {
        if (!is_int($priority) || $priority < 1) {
            throw new \InvalidArgumentException('The priority must be a positive integer');
        }
        $this->values[$priority][] = $value;
        if (!isset($this->priorities[$priority])) {
            $this->priorities[$priority] = $priority;
            $this->max = max($priority, $this->max);
        }
        ++$this->tot;
    }

    /**
     * Extracts a node from the current position of the queue.
     * 
     * @return mixed
     */
    public function extract()
    {
        if (!$this->valid()) {
            return false;
        }
        $value = $this->current();
        $this->next();
        return $value;
    }

    /**
     * Number of elements contained in the queue.
     * 
     * @return int
     */
    public function count()
    {
        return $this->tot;
    }

    /**
     * Current element.
     * 
     * @return mixed
     */
    public function current()
    {
        return current($this->values[$this->max]);
    }

    /**
     * Current element index.
     * 
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Next element on the queue.
     */
    public function next()
    {
        if (false === next($this->values[$this->max])) {
            unset($this->priorities[$this->max]);
            unset($this->values[$this->max]);
            $this->max = empty($this->priorities) ? 0 : max($this->priorities);
        }
        ++$this->index;
        --$this->tot;
    }

    /**
     * Checks if the element in the end of the queue still exists.
     * 
     * @return bool
     */
    public function valid()
    {
        return isset($this->values[$this->max]);
    }

    /**
     * Rewind iterator back to the start (no-op).
     */
    public function rewind()
    {
        // No operation here, never moves from the top of the priority queue
    }
}
