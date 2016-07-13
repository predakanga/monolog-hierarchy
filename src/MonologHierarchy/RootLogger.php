<?php
/**
 * User: predakanga
 * Date: 14/07/2016
 * Time: 12:04 AM
 */

namespace MonologHierarchy;

use MonologHierarchy\Exceptions\DeprecationException;

class RootLogger extends HierarchicalLogger {
    /**
     * @var LoggerManager
     */
    protected $manager;
    /**
     * @var bool
     */
    protected $strictMode;

    public function __construct($name, $handlers, $processors, LoggerManager $manager, bool $strictMode = false) {
        $this->manager = $manager;
        parent::__construct($name, $handlers, $processors);
        $this->strictMode = $strictMode;
    }

    public function withName($name) {
        if($this->strictMode) {
            throw new DeprecationException("Logger::withName is not usable with LoggerHierarchy instances. Use getLogger instead.");
        } else {
            return $this->getLogger($name);
        }
    }

    public function getLogger(string $name) {
        return $this->manager->getLogger($name);
    }
}