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

    /**
     *
     */
    private function afterEnterRunning()
    {
        $this->runCallbackMethods[] = 'afterEnterRunning';
    }

    /**
     *
     */
    private function beforeExitSleeping()
    {
        $this->runCallbackMethods[] = 'beforeExitSleeping';
    }

    /**
     *
     */
    private function exitSleeping()
    {
        $this->runCallbackMethods[] = 'exitSleeping';
    }

    /**
     *
     */
    private function afterExitSleeping()
    {
        $this->runCallbackMethods[] = 'afterExitSleeping';
    }

    /**
     *
     */
    private function beforeRunEvent()
    {
        $this->runCallbackMethods[] = 'beforeRunEvent';
    }

    /**
     *
     */
    private function afterRunEvent()
    {
        $this->runCallbackMethods[] = 'afterRunEvent';
    }

    /**
     *
     */
    private function afterTransition()
    {
        $this->runCallbackMethods[] = 'afterTransition';
    }
}
