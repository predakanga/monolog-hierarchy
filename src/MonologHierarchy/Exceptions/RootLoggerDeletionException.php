<?php
/**
 * User: predakanga
 * Date: 13/07/2016
 * Time: 11:26 PM
 */

namespace MonologHierarchy\Exceptions;


use Exception;

class RootLoggerDeletionException extends \RuntimeException {
    public function __construct($message = "The root logger may only be deleted by deleting the manager object", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}