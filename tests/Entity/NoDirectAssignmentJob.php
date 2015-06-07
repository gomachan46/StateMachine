<?php

namespace StateMachine\Tests\Entity;

use StateMachine\Annotations as SM;
use StateMachine\Traits\StateMachineTrait;

/**
 * NoDirectAssignmentJob
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
 *     },
 *     noDirectAssignment=true
 * )
 */
class NoDirectAssignmentJob
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
