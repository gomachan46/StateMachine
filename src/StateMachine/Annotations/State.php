<?php

namespace StateMachine\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * State annotation for state machine.
 *
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class State extends Annotation
{
    /** @var  string */
    public $name;

    /** @var  string */
    public $beforeEnter;

    /** @var  string */
    public $enter;

    /** @var  string */
    public $afterEnter;
}
