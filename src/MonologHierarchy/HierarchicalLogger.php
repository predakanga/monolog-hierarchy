<?php
/**
 * User: predakanga
 * Date: 14/07/2016
 * Time: 12:33 AM
 */

namespace MonologHierarchy;


use Monolog\Logger;

abstract class HierarchicalLogger extends Logger {
    abstract public function getLogger(string $name);

    // N.B. In order to retain the original name, we need to separate construction and handling of the record
    // FIXME: This is terrible for maintenance.
    public function addRecord($level, $message, array $context = array()) {
        $record = $this->createRecord($level, $message, $context);
        // $record is there for the early exit path of createRecord
        return $record && $this->handleRecord($record);
    }

    protected function createRecord($level, $message, $context) {
        if (!$this->handlers) {
            $this->pushHandler(new StreamHandler('php://stderr', static::DEBUG));
        }

        $levelName = static::getLevelName($level);

        // check if any handler will handle this message so we can return early and save cycles
        $handlerKey = null;
        foreach($this->handlers as $handler) {
            if ($handler->isHandling(array('level' => $level))) {
                $handlerKey = key($this->handlers);
                break;
            }
        }

        if (null === $handlerKey) {
            return false;
        }

        if (!static::$timezone) {
            static::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }

        if ($this->microsecondTimestamps) {
            $ts = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), static::$timezone);
        } else {
            $ts = new \DateTime(null, static::$timezone);
        }
        $ts->setTimezone(static::$timezone);

        return array(
            'message' => (string) $message,
            'context' => $context,
            'level' => $level,
            'level_name' => $levelName,
            'channel' => $this->name,
            'datetime' => $ts,
            'extra' => array(),
        );
    }

    public function handleRecord(array $record): bool {
        foreach ($this->processors as $processor) {
            $record = call_user_func($processor, $record);
        }

        // Switched from current()->next() loop because of some odd issues
        foreach($this->handlers as $handler) {
            if (true === $handler->handle($record)) {
                break;
            }
        }

        return true;
    }
}