<?php
namespace Test;

use FastPriorityQueue\PriorityQueue;
use PHPUnit\Framework\TestCase;

class PriorityQueueTest extends TestCase
{
    public function setUp()
    {
        $this->queue = new PriorityQueue();
    }

    protected function getDataPriorityQueue()
    {
        return [
            'test3' => 2,
            'test5' => 1,
            'test1' => 5,
            'test2' => 3,
            'test4' => 2,
            'test6' => 1
        ];
    }

    protected function insertMixedPriorities(PriorityQueue $queue)
    {
        foreach ($this->getDataPriorityQueue() as $value => $priority) {
            $queue->insert($value, $priority);
        }
    }

    public function testInsertExtractSamePriority()
    {
        $tot = 10;
        for ($i=1; $i<$tot; $i++) {
          $this->queue->insert("test$i", 1);
        }
        for($i=1; $i<$tot; $i++) {
            $this->assertEquals("test$i", $this->queue->extract());
        }
    }

    public function testInsertExtractMixPriority()
    {
        $this->insertMixedPriorities($this->queue);
        $tot = count($this->getDataPriorityQueue());
        for($i=1; $i<$tot; $i++) {
            $this->assertEquals("test$i", $this->queue->extract());
        }
    }

    public function testCountable()
    {
        $this->insertMixedPriorities($this->queue);
        $this->assertEquals(count($this->getDataPriorityQueue()), count($this->queue));
    }

    public function testCount()
    {
        $this->insertMixedPriorities($this->queue);
        $this->assertEquals(count($this->getDataPriorityQueue()), $this->queue->count());
    }

    public function testIterator()
    {
        $this->insertMixedPriorities($this->queue);
        $this->queue->rewind();

        while ($this->queue->valid())
        {
            $key   = $this->queue->key();
            $value = $this->queue->current();
            $this->assertEquals(sprintf("test%s", ++$key), $value);
            $this->queue->next();
        }
        $this->assertFalse($this->queue->valid());
    }

    public function testIteratorUsingForeach()
    {
        $this->insertMixedPriorities($this->queue);

        foreach ($this->queue as $value) {
            $key = $this->queue->key();
            $this->assertEquals(sprintf("test%s", ++$key), $value);
        }
        $this->assertFalse($this->queue->valid());
    }

    public function testNoRewindOperation()
    {
        $this->insertMixedPriorities($this->queue);

        $this->assertEquals(0, $this->queue->key());
        $this->queue->next();
        $this->assertEquals(1, $this->queue->key());
        $this->queue->rewind();
        $this->assertEquals(1, $this->queue->key());
    }
}
