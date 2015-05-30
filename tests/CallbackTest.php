<?php

namespace StateMachine\Tests;

use StateMachine\Tests\Entity\CallbackJob;

class CallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testRunBeforeEnterRunning()
    {
        $job = new CallbackJob();
        $job->run();
        $this->assertTrue($job->isRunBeforeEnterRunning());
    }
}