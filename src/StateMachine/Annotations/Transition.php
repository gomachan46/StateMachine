<?php

namespace StateMachine\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Transition machine annotation for state machine.
 *
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Transition extends Annotation
{
    /** @var  array */
    public $from;

    /** @var  string */
    public $to;

    /** @var  string */
    public $after;
}
