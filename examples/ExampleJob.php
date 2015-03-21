<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__ . '/../vendor/autoload.php';
AnnotationRegistry::registerLoader(array($loader, "loadClass"));




use StateMachine\Annotations as SM;
use StateMachine\Traits\StateMachineTrait;

/**
 * ExampleJob
 *
 * @SM\StateMachine(
 *     property="status",
 *     states={
 *         @SM\State(name="sleeping"),
 *         @SM\State(name="running"),
 *         @SM\State(name="cleaning")
 *     },
 *     events={
 *         @SM\Event(
 *             name="run",
 *             transitions={
 *                 @SM\Transition(from="sleeping", to="running")
 *             }
 *         ),
 *         @SM\Event(
 *             name="clean",
 *             transitions={
 *                 @SM\Transition(from="running", to="cleaning")
 *             }
 *         ),
 *         @SM\Event(
 *             name="sleep",
 *             transitions={
 *                 @SM\Transition(from={"running", "cleaning"}, to="sleeping")
 *             }
 *         )
 *     }
 * )
 */
class ExampleJob
{
    use StateMachineTrait;

    /**
     * @var string
     */
    private $status = 'sleeping';

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}

$job = new ExampleJob();
var_dump("isSleeping(): " . $job->isSleeping());
var_dump("canRun(): " . $job->canRun());
var_dump("run(): " . $job->run());
var_dump("isRunning(): " . $job->isRunning());
var_dump("isSleeping(): " . $job->isSleeping());
var_dump("canRun(): " . $job->canRun());

# please run `php examples/ExampleJob.php`
