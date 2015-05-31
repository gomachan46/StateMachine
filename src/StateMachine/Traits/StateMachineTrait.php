<?php

namespace StateMachine\Traits;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Inflector\Inflector;
use StateMachine\Annotations\State;
use StateMachine\Annotations\StateMachine;
use StateMachine\Exceptions\InvalidTransitionException;
use StateMachine\Exceptions\NoDirectAssignmentException;
use StateMachine\Exceptions\NotFoundAnnotationException;
use StateMachine\Exceptions\NotFoundStateException;

/**
 * Trait StateMachineTrait
 *
 * Methods and Properties that have been made by 'StateMachineTrait' have 'SM' suffix.
 */
trait StateMachineTrait
{
    /**
     * Subject to be managed by state machine.
     *
     * @var null|string
     */
    private $stateMachineStatusSM = null;

    /**
     * State machine add methods to Entity.
     * Array keys are method names, and values are method behavior. (This is a Closure)
     *
     * @var null|\Closure[]
     */
    private $methodsSM = null;

    /**
     * Annotations for state machine.
     *
     * @var null|StateMachine
     */
    private $stateMachineAnnotationsSM = null;

    /**
     * Execute closure if $methods have a key named $name.
     * If not have, raise user_error.
     *
     * @param       $name
     * @param array $args
     *
     * @return mixed
     */
    public function __call($name, array $args)
    {
        $this->setUpSM();
        if (!array_key_exists($name, $this->methodsSM)) {
            trigger_error(sprintf('Call to undefined method: %s::%s().', get_class($this), $name), E_USER_ERROR);
        }

        $func = $this->methodsSM[$name];

        return call_user_func_array($func->bindTo($this, get_class($this)), $args);
    }

    /**
     * Set up state machine if needed.
     *
     * @throws NotFoundAnnotationException
     */
    private function setUpSM()
    {
        if (is_null($this->stateMachineAnnotationsSM)) {
            $this->setStateMachineAnnotationsSM();
        }
        if (is_null($this->stateMachineStatusSM)) {
            $this->stateMachineStatusSM = $this->stateMachineAnnotationsSM->property;
        }
        if (is_null($this->methodsSM)) {
            $this->addMethodsSM();
        }
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return $this
     */
    private function setEntityStatusSM($status)
    {
        $entityStatus = $this->stateMachineStatusSM;
        $this->$entityStatus = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    private function getEntityStatusSM()
    {
        $entityStatus = $this->stateMachineStatusSM;

        return $this->$entityStatus;
    }

    /**
     * set state machine annotations
     *
     * @return $this
     * @throws \Exception if annotation is not defined
     */
    private function setStateMachineAnnotationsSM()
    {
        $reader = new AnnotationReader();
        $this->stateMachineAnnotationsSM = $reader->getClassAnnotation(
            new \ReflectionClass($this),
            'StateMachine\Annotations\StateMachine'
        );
        if (!$this->stateMachineAnnotationsSM) {
            throw new NotFoundAnnotationException('Not found @StateMachine annotation to class');
        }

        return $this;
    }

    /**
     * Add the required methods for state machine
     */
    private function addMethodsSM()
    {
        $events = $this->stateMachineAnnotationsSM->events;
        foreach ($events as $event) {
            $this->addEventExecuteMethodSM($event);
            $this->addEventCanExecuteMethodSM($event);
        }
        $states = $this->stateMachineAnnotationsSM->states;
        foreach ($states as $state) {
            $this->addIsStateMethodSM($state);
        }
        $this->addSetStatusMethodSM();
    }

    /**
     * @param $event
     * @return bool
     */
    private function addEventExecuteMethodSM($event)
    {
        $methodName = Inflector::camelize($event->name);

        /**
         * Transition from current state to another state in accordance with the transition definition.
         * If can not transition, throw InvalidTransitionException.
         *
         * @throws InvalidTransitionException
         */
        $cb = function () use ($event) {
            $transitions = $event->transitions;
            foreach ($transitions as $transition) {
                if (!$this->canTransitionSM($transition->from)) {
                    continue;
                }
                $fromState = $this->findStateByNameSM($transition->from);
                $toState = $this->findStateByNameSM($transition->to);

                $this->executeEntityMethodSM($fromState->beforeExit);
                $this->executeEntityMethodSM($fromState->exit);
                $this->executeEntityMethodSM($toState->beforeEnter);
                $this->executeEntityMethodSM($toState->enter);
                $this->setEntityStatusSM($transition->to);
                $this->executeEntityMethodSM($fromState->afterExit);
                $this->executeEntityMethodSM($toState->afterEnter);

                return true;
            }
            if ($this->stateMachineAnnotationsSM->whinyTransitions) {
                return false;
            } else {
                throw new InvalidTransitionException(
                    sprintf('Invalid transition. event: %s, from: %s', $event->name, $this->getEntityStatusSM())
                );
            }
        };
        $this->methodsSM[$methodName] = $cb;
    }

    /**
     * @param $event
     */
    private function addEventCanExecuteMethodSM($event)
    {
        $methodName = 'can' . ucfirst(Inflector::camelize($event->name));

        /**
         * Verify executable event in accordance with the transition definitions.
         *
         * @return bool
         */
        $cb = function () use ($event) {
            $transitions = $event->transitions;
            foreach ($transitions as $transition) {
                if (!$this->canTransitionSM($transition->from)) {
                    continue;
                }

                return true;
            }

            return false;
        };
        $this->methodsSM[$methodName] = $cb;
    }

    /**
     * @param $state
     */
    private function addIsStateMethodSM($state)
    {
        $methodName = 'is' . ucfirst(Inflector::camelize($state->name));

        /**
         * Verify current state is $state->name.
         *
         * @return bool
         */
        $cb = function () use ($state) {
            return $this->getEntityStatusSM() === $state->name;
        };
        $this->methodsSM[$methodName] = $cb;
    }

    /**
     *
     */
    private function addSetStatusMethodSM()
    {
        $methodName = 'set' . ucfirst(Inflector::camelize($this->stateMachineStatusSM));

        /**
         * Try to transition by direct assign.
         * If can not transition, throw NoDirectAssignmentException.
         *
         * @param $status
         *
         * @return $this
         * @throws InvalidTransitionException
         * @throws NoDirectAssignmentException
         */
        $cb = function ($status) {
            if ($this->getEntityStatusSM() === $status) {
                // nothing to do
                return $this;
            }
            $events = $this->stateMachineAnnotationsSM->events;
            foreach ($events as $event) {
                $transitions = $event->transitions;
                foreach ($transitions as $transition) {
                    if ($this->canTransitionSM($transition->from) && $status === $transition->to) {
                        $this->setEntityStatusSM($status);

                        return $this;
                    }
                }
            }

            throw new NoDirectAssignmentException(
                sprintf('Can not assign direct. current: %s, will: %s', $this->getEntityStatusSM(), $status)
            );
        };
        $this->methodsSM[$methodName] = $cb;
    }

    /**
     * @param $from
     *
     * @return bool
     * @throws InvalidTransitionException
     */
    private function canTransitionSM($from)
    {
        if (is_array($from)) {
            return in_array($this->getEntityStatusSM(), $from);
        } elseif (is_string($from)) {
            return $this->getEntityStatusSM() === $from;
        } else {
            throw new InvalidTransitionException(
                sprintf('Invalid "from" value. There must be an array or string. from: %s', $from)
            );
        }
    }

    /**
     * @param $stateName
     *
     * @return State
     * @throws NotFoundStateException
     */
    private function findStateByNameSM($stateName)
    {
        $state = array_filter(
            $this->stateMachineAnnotationsSM->states,
            function (State $state) use ($stateName) {
                return $state->name === $stateName;
            }
        );
        $state = array_shift($state);

        if (is_null($state)) {
            throw new NotFoundStateException("State not found. stateName: $stateName");
        }

        return $state;
    }

    /**
     * @param $methodName
     *
     * @return mixed
     */
    private function executeEntityMethodSM($methodName)
    {
        if (!$methodName) {
            return null;
        }

        return $this->$methodName();
    }
}
