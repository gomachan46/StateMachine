<?php

namespace StateMachine\Tests;

use StateMachine\Tests\Entity\CallbackJob;
use StateMachine\Tests\Entity\CallbackWithArgsJob;

/**
 * Class CallbackTest
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider callbackMethods
     */
    public function testRunCallbackMethods($expected)
    {
        $job = new CallbackJob();
        $job->run();
        $this->assertTrue(in_array($expected, $job->getRunCallbackMethods()));
    }

    /**
     * @return array
     */
    public function callbackMethods()
    {
        return [
            ['beforeEnterRunning'],
            ['enterRunning'],
            ['afterEnterRunning'],
            ['beforeExitSleeping'],
            ['exitSleeping'],
            ['afterExitSleeping'],
            ['beforeRunEvent'],
            ['afterRunEvent'],
            ['afterTransition']
        ];
    }

    /**
     * @dataProvider callbackMethodsWithArgs
     */
    public function testRunCallbackMethodsWithArgs($expected)
    {
        $job = new CallbackWithArgsJob();
        $job->run('arg1', 'arg2');
        $this->assertTrue(in_array($expected, $job->getRunCallbackMethods()));
    }

    /**
     * @return array
     */
    public function callbackMethodsWithArgs()
    {
        return [
            ['beforeEnterRunning: arg1, arg2'],
            ['enterRunning: arg1, arg2'],
            ['afterEnterRunning: arg1, arg2'],
            ['beforeExitSleeping: arg1, arg2'],
            ['exitSleeping: arg1, arg2'],
            ['afterExitSleeping: arg1, arg2'],
            ['beforeRunEvent: arg1, arg2'],
            ['afterRunEvent: arg1, arg2'],
            ['afterTransition: arg1, arg2'],
        ];
    }

    /**
     * @dataProvider callbackMethodsAndOrder
     */
    public function testRunCallbackMethodsInTheCorrectOrder($index, $expected)
    {
        $job = new CallbackJob();
        $job->run();
        $this->assertSame($expected, $job->getRunCallbackMethods()[$index]);
    }

    /**
     * @return array
     */
    public function callbackMethodsAndOrder()
    {
        return [
            [0, 'beforeRunEvent'],
            [1, 'beforeExitSleeping'],
            [2, 'exitSleeping'],
            [3, 'beforeEnterRunning'],
            [4, 'enterRunning'],
            [5, 'afterTransition'],
            [6, 'afterExitSleeping'],
            [7, 'afterEnterRunning'],
            [8, 'afterRunEvent'],
        ];
    }
}
