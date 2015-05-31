<?php

namespace StateMachine\Tests\Entity;

use StateMachine\Annotations as SM;
use StateMachine\Traits\StateMachineTrait;

/**
 * CallbackJob
 *
 * @SM\StateMachine(
 *     property="status",
 *     states={
 *         @SM\State(name="sleeping"),
 *         @SM\State(name="running", beforeEnter="beforeEnterRunning", enter="enterRunning"),
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
class CallbackJob
{
    use StateMachineTrait;

    /**
     * @var string
     */
    private $status = 'sleeping';

    /**
     * @var string[]
     */
    private $runCallbackMethods = [];

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string[]
     */
    public function getRunCallbackMethods()
    {
        return $this->runCallbackMethods;
    }

    /**
     *
     */
    private function beforeEnterRunning()
    {
        $this->runCallbackMethods[] = 'beforeEnterRunning';
    }

    /**
     *
     */
    private function enterRunning()
    {
        $this->runCallbackMethods[] = 'enterRunning';
    }
}
