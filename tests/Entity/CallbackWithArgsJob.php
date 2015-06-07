<?php

namespace StateMachine\Tests\Entity;

use StateMachine\Annotations as SM;
use StateMachine\Traits\StateMachineTrait;

/**
 * CallbackWithArgsJob
 *
 * @SM\StateMachine(
 *     property="status",
 *     states={
 *         @SM\State(
 *             name="sleeping",
 *             beforeExit="beforeExitSleeping",
 *             exit="exitSleeping",
 *             afterExit="afterExitSleeping"
 *         ),
 *         @SM\State(
 *             name="running",
 *             beforeEnter="beforeEnterRunning",
 *             enter="enterRunning",
 *             afterEnter="afterEnterRunning"
 *         ),
 *         @SM\State(name="cleaning")
 *     },
 *     events={
 *         @SM\Event(
 *             name="run",
 *             transitions={
 *                 @SM\Transition(
 *                     from="sleeping",
 *                     to="running",
 *                     after="afterTransition"
 *                 )
 *             },
 *             before="beforeRunEvent",
 *             after="afterRunEvent"
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
class CallbackWithArgsJob
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
        $args = func_get_args();
        $this->runCallbackMethods[] = 'beforeEnterRunning: '.join(', ', $args);
    }

    /**
     *
     */
    private function enterRunning()
    {
        $args = func_get_args();
        $this->runCallbackMethods[] = 'enterRunning: '.join(', ', $args);
    }

    /**
     *
     */
    private function afterEnterRunning()
    {
        $args = func_get_args();
        $this->runCallbackMethods[] = 'afterEnterRunning: '.join(', ', $args);
    }

    /**
     *
     */
    private function beforeExitSleeping()
    {
        $args = func_get_args();
        $this->runCallbackMethods[] = 'beforeExitSleeping: '.join(', ', $args);
    }

    /**
     *
     */
    private function exitSleeping()
    {
        $args = func_get_args();
        $this->runCallbackMethods[] = 'exitSleeping: '.join(', ', $args);
    }

    /**
     *
     */
    private function afterExitSleeping()
    {
        $args = func_get_args();
        $this->runCallbackMethods[] = 'afterExitSleeping: '.join(', ', $args);
    }

    /**
     *
     */
    private function beforeRunEvent()
    {
        $args = func_get_args();
        $this->runCallbackMethods[] = 'beforeRunEvent: '.join(', ', $args);
    }

    /**
     *
     */
    private function afterRunEvent()
    {
        $args = func_get_args();
        $this->runCallbackMethods[] = 'afterRunEvent: '.join(', ', $args);
    }

    /**
     *
     */
    private function afterTransition()
    {
        $args = func_get_args();
        $this->runCallbackMethods[] = 'afterTransition: '.join(', ', $args);
    }
}
