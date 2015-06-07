<?php

namespace StateMachine\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Event annotation for state machine.
 *
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Event extends Annotation
{
    /** @var  string */
    public $name;


    /** @var array<\StateMachine\Annotations\Transition> */
    public $transitions;

    /** @var  string */
    public $before;

    /** @var  string */
    public $after;
}
