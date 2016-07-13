<?php
/**
 * User: predakanga
 * Date: 12/07/2016
 * Time: 11:20 PM
 */

namespace MonologHierarchy\Handlers;


use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractHandler;
use Monolog\Logger;
use MonologHierarchy\Exceptions\ImmutableException;
use MonologHierarchy\LoggerManager;

class HierarchyHandler extends AbstractHandler{
    /**
     * @var LoggerManager
     */
    protected $manager;
    /**
     * @var string
     */
    protected $parentName;

    /**
     * Handles a record.
     *
     * All records may be passed to this method, and the handler should discard
     * those that it does not want to handle.
     *
     * The return value of this function controls the bubbling process of the handler stack.
     * Unless the bubbling is interrupted (by returning true), the Logger class will keep on
     * calling further handlers in the stack with a given log record.
     *
     * @param  array $record The record to handle
     *
     * @return Boolean true means that this handler handled the record, and that bubbling is not permitted.
     *                        false means the record was either not processed or that this handler allows bubbling.
     */
    public function handle(array $record) {
        // Fetch the logger on each handling in case an intermediate logger has been created recently
        $logger = $this->manager->getLoggerOrParent($this->parentName);
        // Extract the original arguments
        // N.B. This does mean that the timestamp is constructed twice
        return $logger->handleRecord($record);
    }

    public function __construct(LoggerManager $manager, string $parentName) {
        $this->level = Logger::DEBUG;
        $this->bubble = false;

        $this->manager = $manager;
        $this->parentName = $parentName;
    }

    public function pushProcessor($callback) {
        throw new ImmutableException();
    }

    public function popProcessor() {
        throw new ImmutableException();
    }

    public function setFormatter(FormatterInterface $formatter) {
        throw new ImmutableException();
    }

    public function setLevel($level) {
        throw new ImmutableException();
    }

    public function setBubble($bubble) {
        throw new ImmutableException();
    }
}