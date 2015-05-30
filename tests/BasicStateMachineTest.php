<?php

namespace StateMachine\Tests;

use StateMachine\Exceptions\InvalidTransitionException;
use StateMachine\Exceptions\NoDirectAssignmentException;
use StateMachine\Tests\Entity\Job;

/**
 * Class BasicStateMachineTest
 */
class BasicStateMachineTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Job */
    private $job;

    public function setUp()
    {
        $this->job = new Job();
    }

    /**
     * @dataProvider isStateMethods
     */
    public function testIsStateMethod($method, $expected)
    {
        $this->assertSame($expected, $this->job->$method());
    }

    /**
     * @return array
     */
    public function isStateMethods()
    {
        return [
            ['isSleeping', true],
            ['isRunning', false],
            ['isCleaning', false],
        ];
    }

    public function testValidTransitionEventMethod()
    {
        $this->assertSame('sleeping', $this->job->getStatus());
        $this->job->run();
        $this->assertSame('running', $this->job->getStatus());
    }

    public function testTransitionEventMethodReturnTrue()
    {
        $this->assertTrue($this->job->run());
    }

    /**
     * @dataProvider invalidTransitionEventMethods
     */
    public function testNotChangeStatusInvalidTransitionEventMethod($method)
    {
        $this->assertSame('sleeping', $this->job->getStatus());
        try {
            $this->job->$method();
        } catch (InvalidTransitionException $e) {
            $this->assertSame('sleeping', $this->job->getStatus());

            return;
        }

        $this->fail();
    }

    /**
     * @dataProvider invalidTransitionEventMethods
     */
    public function testRaiseExceptionInvalidTransitionEventMethod($method)
    {
        try {
            $this->job->$method();
        } catch (InvalidTransitionException $e) {
            $this->assertSame(
                "Invalid transition. event: $method, from: sleeping",
                $e->getMessage()
            );

            return;
        }

        $this->fail();
    }

    /**
     * @return array
     */
    public function invalidTransitionEventMethods()
    {
        return [
            ['clean'],
            ['sleep'],
        ];
    }

    /**
     * @dataProvider canTransitionEventMethod
     *
     * @param $method
     * @param $expected
     */
    public function testCanTransitionEventMethods($method, $expected)
    {
        $this->assertSame($expected, $this->job->$method());
    }

    /**
     * @return array
     */
    public function canTransitionEventMethod()
    {
        return [
            ['canRun', true],
            ['canClean', false],
            ['canSleep', false],
        ];
    }

    /**
     * @dataProvider validDirectAssign
     */
    public function testValidDirectAssign($status)
    {
        $this->assertSame('sleeping', $this->job->getStatus());
        $this->job->setStatus($status);
        $this->assertSame($status, $this->job->getStatus());
    }

    /**
     * @return array
     */
    public function validDirectAssign()
    {
        return [
            ['sleeping'],
            ['running'],
        ];
    }

    /**
     *
     */
    public function testInvalidDirectAssign()
    {
        $this->assertSame('sleeping', $this->job->getStatus());
        try {
            $this->job->setStatus('cleaning');
        } catch (NoDirectAssignmentException $e) {
            $this->assertSame("Can not assign direct. current: sleeping, will: cleaning", $e->getMessage());

            return;
        }

        $this->fail();
    }
}
