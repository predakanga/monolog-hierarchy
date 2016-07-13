<?php
/**
 * User: predakanga
 * Date: 12/07/2016
 * Time: 11:34 PM
 */

namespace MonologHierarchy\Exceptions;


class ImmutableException extends \LogicException {
    public function __construct($message = "The class you attempted to modify is immutable", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}