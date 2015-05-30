<?php

use StateMachine\Tests\Entity\WhinyTransitionsJob;

/**
 * Class WhinyTransitionsTest
 */
class WhinyTransitionsTest extends PHPUnit_Framework_TestCase
{
    /** @var WhinyTransitionsJob $job  */
    private $job;

    /**
     *
     */
    public function setUp()
    {
        $this->job = new WhinyTransitionsJob();
    }

    /**
     *
     */
    public function testWhinyTransitionEventMethodReturnFalse()
    {
        $this->assertFalse($this->job->sleep());
    }
}
