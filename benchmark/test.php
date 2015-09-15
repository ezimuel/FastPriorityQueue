<?php
/**
 * Benchmark the execution time of FastPriorityQueue
 * compared with SplPriorityQueue and other implementations
 */
require_once __DIR__ . '/../vendor/autoload.php';

use FastPriorityQueue\PriorityQueue as FastPriorityQueue;
use Zend\Stdlib\SplPriorityQueue as ZendSplPriorityQueue;
use Zend\Stdlib\PriorityQueue as ZendPriorityQueue;

$numIterations = 50000;

printf("\n--- Benchmark %s insert and extract with a fixed priority\n", number_format($numIterations));
$times = [];

$start = microtime(true);
$splQueue = new \SplPriorityQueue();
benchmark($numIterations, $splQueue);
$times['spl'] = microtime(true) - $start;

$start = microtime(true);
$fastQueue = new FastPriorityQueue();
benchmark($numIterations, $fastQueue);
$times['fast'] = microtime(true) - $start;

$start = microtime(true);
$zendSplQueue = new ZendSplPriorityQueue();
benchmark($numIterations, $zendSplQueue);
$times['zend_spl'] = microtime(true) - $start;

$start = microtime(true);
$zendPriorityQueue = new ZendPriorityQueue();
benchmark($numIterations, $zendPriorityQueue);
$times['zend_priority'] = microtime(true) - $start;

print_results($times);

printf("\n--- Benchmark %s insert and extract with random priority (1,100)\n", number_format($numIterations));
$times = [];

$start = microtime(true);
$splQueue = new \SplPriorityQueue();
benchmark($numIterations, $splQueue);
$times['spl'] = microtime(true) - $start;

$start = microtime(true);
$fastQueue = new FastPriorityQueue();
benchmark_random($numIterations, $fastQueue);
$times['fast'] = microtime(true) - $start;

$start = microtime(true);
$zendSplQueue = new ZendSplPriorityQueue();
benchmark_random($numIterations, $zendSplQueue);
$times['zend_spl'] = microtime(true) - $start;

$start = microtime(true);
$zendPriorityQueue = new ZendPriorityQueue();
benchmark_random($numIterations, $zendPriorityQueue);
$times['zend_priority'] = microtime(true) - $start;

print_results($times);

printf ("\n* The PHP SplPriorityQueue with the order issue (https://bugs.php.net/bug.php?id=60926)\n");

// insert and extract $tot elements with priority 1
function benchmark($tot, $queue)
{
    for($i=0; $i<$tot; $i++) {
        $queue->insert("This is a test!", 1);
    }
    for($i=0; $i<$tot; $i++) {
        if ("This is a test!" !== $queue->extract()) {
            printf("ERROR: the extracted value is wrong!\n");
            exit();
        }
    }
}

// insert and extract $tot elements with priority rand(1,100)
function benchmark_random($tot, $queue)
{
    for($i=0; $i<$tot; $i++) {
        $queue->insert("This is a test!", rand(1,100));
    }
    for($i=0; $i<$tot; $i++) {
        $queue->extract();
    }
}

// print the results
function print_results($times)
{
    $min = min($times);
    printf ("SplPriorityQueue*               : %.8f (sec)\n", $times['spl']);
    printf ("FastPriorityQueue\PriorityQueue : %.8f (sec)\n", $times['fast']);
    printf ("Zend\Stdlib\SplPriorityQueue    : %.8f (sec)\n", $times['zend_spl']);
    printf ("Zend\Stdlib\PriorityQueue       : %.8f (sec)\n", $times['zend_priority']);
}
