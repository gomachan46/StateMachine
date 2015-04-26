<?php

namespace StateMachine\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * State machine annotation for state machine.
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class StateMachine extends Annotation
{
    /** @var  string */
    public $property;

    /** @var array<\StateMachine\Annotations\State> */
    public $states;

    /** @var array<\StateMachine\Annotations\Event> */
    public $events;

    /** @var  boolean */
    public $whinyTransitions;
}
