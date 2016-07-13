<?php
/**
 * User: predakanga
 * Date: 12/07/2016
 * Time: 11:17 PM
 */

namespace MonologHierarchy;

use MonologHierarchy\Handlers\HierarchyHandler;

class Logger extends RootLogger {
    /**
     * @var HierarchyHandler
     */
    protected $hierarchyHandler;

    public function __construct($name, $handlers, $processors, LoggerManager $manager, bool $strictMode = false) {
        $this->hierarchyHandler = new HierarchyHandler($manager, $manager->getParentName($name));
        array_push($handlers, $this->hierarchyHandler);

        parent::__construct($name, $handlers, $processors, $manager, $strictMode);
    }

    // N.B. Turns out Monolog uses pop and push differently from the rest of the world.
    //      It actually means shift and unshift.
    public function popHandler() {
        // Remove our special handler before handing over to the super function
        $lastHandler = array_shift($this->handlers);
        assert($lastHandler == $this->hierarchyHandler);

        $toRet = parent::popHandler();

        array_unshift($this->handlers, $lastHandler);

        return $toRet;
    }

    public function setHandlers(array $handlers) {
        array_push($handlers, $this->hierarchyHandler);
        return parent::setHandlers($handlers);
    }
}