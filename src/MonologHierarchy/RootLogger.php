<?php
/**
 * User: predakanga
 * Date: 14/07/2016
 * Time: 12:04 AM
 */

namespace MonologHierarchy;

use Monolog\Logger as BaseLogger;
use MonologHierarchy\Exceptions\DeprecationException;

class RootLogger extends HierarchicalLogger {
    /**
     * @var LoggerManager
     */
    protected $manager;

    public function __construct($name, $handlers, $processors, LoggerManager $manager) {
        $this->manager = $manager;
        parent::__construct($name, $handlers, $processors);
    }

    public function withName($name) {
        throw new DeprecationException("Logger::withName is not usable with LoggerHierarchy instances. Use getLogger instead.");
    }

    public function getLogger(string $name) {
        return $this->manager->getLogger($name);
    }
}