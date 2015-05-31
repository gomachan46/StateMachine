<?php

namespace StateMachine\Tests;

use StateMachine\Tests\Entity\CallbackJob;

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
        ];
    }
}
