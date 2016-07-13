<?php
/**
 * User: predakanga
 * Date: 13/07/2016
 * Time: 11:38 PM
 */

namespace MonologHierarchy;


class LogHierarchyBuilder {
    protected $definitions = [];

    protected function createManager() {
        return new LoggerManager();
    }

    public function build(): LoggerManager {
        $manager = $this->createManager();
        
        return $manager;
    }
}