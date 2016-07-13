<?php
/**
 * User: predakanga
 * Date: 12/07/2016
 * Time: 11:17 PM
 */

namespace MonologHierarchy;

use MonologHierarchy\Exceptions\RootLoggerDeletionException;

class LoggerManager {
    protected $loggers = [];
    const NAME_DELIMITER = '.';

    /**
     * LoggerManager constructor.
     *
     * @param Logger $rootLogger Root logger (optional)
     */
    public function __construct(HierarchicalLogger $rootLogger = null) {
        $this->loggers[''] = $rootLogger ?: $this->createRootLogger();
    }

    protected function createRootLogger(): HierarchicalLogger {
        return new RootLogger('', [], [], $this);
    }

    protected function createLogger(string $name): HierarchicalLogger {
        return new Logger($name, [], [], $this);
    }

    public function getRootLogger(): HierarchicalLogger {
        return $this->loggers[''];
    }

    public function deleteLogger(string $name) {
        if($name == '') {
            throw new RootLoggerDeletionException();
        }

        if($this->hasLogger($name)) {
            unset($this->loggers[$name]);
        }
    }

    public function hasLogger(string $name): bool {
        return isset($this->loggers[$name]) && $this->loggers[$name];
    }

    public function getLoggerOrParent(string $name): HierarchicalLogger {
        $foundName = $name;
        while(!$this->hasLogger($foundName)) {
            $foundName = $this->getParentName($foundName);
        }

        return $this->getLogger($foundName);
    }

    public function getLogger(string $name): HierarchicalLogger {
        if(!$this->hasLogger($name)) {
            $this->loggers[$name] = $this->createLogger($name);
        }
        return $this->loggers[$name];
    }

    public function getParentName(string $name): string {
        $pivot = strrpos($name, static::NAME_DELIMITER);
        if($pivot === FALSE) {
            return '';
        } else {
            return substr($name, 0, $pivot);
        }
    }
}