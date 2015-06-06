<?php

namespace StateMachine\Tests;

use StateMachine\Exceptions\NoDirectAssignmentException;
use StateMachine\Tests\Entity\NoDirectAssignmentJob;

/**
 * Class NoDirectAssignmentOptionTest
 */
class NoDirectAssignmentOptionTest extends \PHPUnit_Framework_TestCase
{
    /** @var NoDirectAssignmentJob $job  */
    private $job;

    /**
     *
     */
    public function setUp()
    {
        $this->job = new NoDirectAssignmentJob();
    }

    /**
     *
     */
    public function testNoDirectAssignmentOptionEnabled()
    {
        try {
            $this->job->setStatus('running');
        } catch (NoDirectAssignmentException $e) {
            $this->assertSame('Can not assign direct because no direct assignment option enabled.', $e->getMessage());
            return;
        }
        $this->fail();
    }
}
