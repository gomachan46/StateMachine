<?php

namespace StateMachine\Tests;

use StateMachine\Tests\Entity\CallbackJob;

/**
 * Class CallbackTest
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testRunBeforeEnterRunning()
    {
        $job = new CallbackJob();
        $job->run();
        $this->assertTrue(in_array('beforeEnterRunning', $job->getRunCallbackMethods()));
    }

    /**
     *
     */
    public function testRunEnterRunning()
    {
        $job = new CallbackJob();
        $job->run();
        $this->assertTrue(in_array('enterRunning', $job->getRunCallbackMethods()));
    }
}
