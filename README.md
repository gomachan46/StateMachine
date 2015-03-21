StateMachine
====

StateMachine is a simple state machine with annotations for PHP, inspired by AASM known as a Ruby state machine.

## Description

StateMachine is ... ?

* using some features of Doctrine. doctrine/annotations and doctrine/inflector only.
  * Doctrine is known as ORM, but StateMachine is not related to the database.
  * StateMachine is anything available if the PHP class.
* composed of Doctrine's custom annotations and traits.
* very easily available. Describe the annotations and `use StateMachineTrait` to what you want to manage the state, only this.
* provides `@StateMachine`, `@State`, `Event`, and `@Transition` annotations.

## Demo

As an example, you want to manage the states of Job class.

```
Job has `sleeping`, `running`, and `cleaning` states.

Transition to be forgiven, 

* from `sleeping` to `running`
* from `running` to `cleaning`
* from `sleeping` or `cleaning` to `cleaning`

Job's initial state is `sleeping`.
```

This case, you can use StateMachine, only this.

```
use StateMachine\Annotations as SM;
use StateMachine\Traits\StateMachineTrait;

/**
 * Job
 *
 * @SM\StateMachine(
 *     property="status",
 *     states={
 *         @SM\State(name="sleeping"),
 *         @SM\State(name="running"),
 *         @SM\State(name="cleaning")
 *     },
 *     events={
 *         @SM\Event(
 *             name="run",
 *             transitions={
 *                 @SM\Transition(from="sleeping", to="running")
 *             }
 *         ),
 *         @SM\Event(
 *             name="clean",
 *             transitions={
 *                 @SM\Transition(from="running", to="cleaning")
 *             }
 *         ),
 *         @SM\Event(
 *             name="sleep",
 *             transitions={
 *                 @SM\Transition(from={"running", "cleaning"}, to="sleeping")
 *             }
 *         )
 *     }
 * )
 */
class Job
{
    use StateMachineTrait;

    /**
     * @var string
     */
    private $status = 'sleeping';

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
```

So simple! So easy!

## VS. 

StateMachine vs. Other state machine for PHP ...

* using annotations.
* using trait.
* no configuration files.
* no state object.
* no state machine class, only trait.
* no state machine factory.
* when state changed, is immediately reflected.

## Requirement

StateMachine works with PHP 5.4.0 or later.

## Installation (via composer)

```
{
    "require": {
        "gomachan46/state-machine": "~1.0"
    }
}
```

## Usage

Adding a state machine is as simple as:

```
use StateMachine\Annotations as SM;
use StateMachine\Traits\StateMachineTrait;

/**
 * state machine annotations ...
 */
 
class ClassName
{
    use StateMachineTrait;
    
    private $status = 'initial status';
    
    ...
```

and start defining states and events together with their transitions.

### Basic Setting

```
## use StateMachine;
use StateMachine\Annotations as SM;
use StateMachine\Traits\StateMachineTrait;

/**
 * Job
 *
 * @SM\StateMachine(
 *     property="status", // write you want to manage states property name
 *     states={ // all states write here
 *         @SM\State(name="sleeping"), // isSleeping() is available
 *         @SM\State(name="running"), // isRunning() is available
 *         @SM\State(name="cleaning") // isCleaning() is available
 *     },
 *     events={ // all events write here
 *         @SM\Event(
 *             name="run", // run() and canRun() are available
 *             transitions={
 *                 @SM\Transition(from="sleeping", to="running")
 *             }
 *         ),
 *         @SM\Event(
 *             name="clean", clean() and canClean() are available
 *             transitions={
 *                 @SM\Transition(from="running", to="cleaning")
 *             }
 *         ),
 *         @SM\Event(
 *             name="sleep", // sleep() and canSleep() are available
 *             transitions={
 *                 @SM\Transition(from={"running", "cleaning"}, to="sleeping") // "from" can be set multiple state
 *             }
 *         )
 *     }
 * )
 */
class Job
{
    use StateMachineTrait; // Do not forget it!

    /**
     * @var string
     */
    private $status = 'sleeping'; // write initial state.

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * StateMachine added methods setStatus() automatically.
     * Please be careful if override setStatus() method.
     * I recommend that you do not override. (if you manage the state appropriately)
     */ 
    // public function setStatus()
    // {
    // }
}
```

### Annotations description

* `@SM\StateMachine`
  * `property`
    target property name to manage the states (e.g. property="status")  
    this name is used to like `setStatus()`
  * `states`
  * `events`
* `@SM\State`
  * name
    state name (e.g. name="sleeping")
* `@SM\Event`
  * name
    event name (e.g. name="run")  
    this name is used to like `run() canRun()`
  * transitions
* `@SM\Transition`
  * from
    transition from ... (e.g. from="sleeping")  
    you can set array or string.
  * to
    transition to ... (e.g. to="running")

### Provides methods

#### Basic methods

StateMachine provides some methods.

```
$job = new Job();
$job->isSleeping(); // true
$job->canRun(); // true
$job->run();
$job->isRunning(); // true
$job->isSleeping(); // false
$job->canRun(); // false
$job->run(); // raises StateMachine\Exceptions\InvalidTransitionException
```

#### Direct assign method

StateMachine support direct assign.

```
$job = new Job();
$job->getStatus(); // 'sleeping'
$job->setStatus('running'); // return $job
$job->getStatus(); // 'running'
$job->setStatus('sleeping'); // raises StateMachine\Exceptions\NoDirectAssignmentException
```

## In future

In future, I am going to implement like AASM provides services:

* whinyTransition option
* NoDirectAssign option
* Callbacks
* Guards
* Inspection
* and more ...

## Questions

* create an issue on GitHub
* send us a tweet [@gomachan46](https://twitter.com/gomachan46)
* send mail shiro.gomachan46@gmail.com

Feel free!

## Contribution

1. Please fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :)

I'm looking forward to your contributions!

## Licence

[MIT](https://github.com/gomachan46/StateMachine/blob/master/LICENCE)

## Author

[gomachan46](https://github.com/gomachan46)

## Acknowledgement

[aasm](https://github.com/aasm/aasm)

Thank you for the great product.
