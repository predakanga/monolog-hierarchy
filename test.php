<?php
/**
 * User: predakanga
 * Date: 14/07/2016
 * Time: 12:02 AM
 */

use Monolog\Handler\StreamHandler;
use MonologHierarchy\Logger;

require_once("vendor/autoload.php");

// Demo setup:
// All ERROR messages are logged to stderr
// All INFO messages in pack.* are logged to stdout
// All messages in pack.security are logged to file
// All messages in pack.debug are logged to file, without bubbling

$factory = new MonologHierarchy\LoggerManager();

$factory->getRootLogger()->pushHandler(new StreamHandler("php://stderr", Logger::ERROR));
$factory->getLogger('pack')->pushHandler(new StreamHandler("php://stdout", Logger::INFO));
$factory->getLogger('pack.security')->pushHandler(new StreamHandler('/tmp/pack-security.log', Logger::DEBUG));
$factory->getLogger('pack.debug')->pushHandler(new StreamHandler('/tmp/pack-debug.log', Logger::DEBUG, false));

$reqLog = $factory->getLogger('pack.requests');
$sec1Log = $factory->getLogger('pack.security.authn');
$sec2Log = $factory->getLogger('pack.security.authz');
$debLog = $factory->getLogger('pack.debug.some.long.and.complicated.class');
$othLog = $factory->getLogger('webapp');

// Should be logged to stdout *and* stderr
$reqLog->error('Failed to decode HTTP request');
// Should be logged to stdout
$reqLog->warn('User being throttled');
// Should be swallowed
$reqLog->debug('Uninitialized request ID');
// Should be logged to file and console
$sec1Log->info('User predakanga authorized by cookie');
$sec2Log->info('User predakanga granted permission users_edit by group Administrators');
$sec1Log->info('User predakanga began impersonating AzzA');
// Should only be logged to file
$sec1Log->debug('Rotating XSRF tokens for user predakanga');

// Should all go to file, not console
$debLog->debug('Mark 1');
$debLog->info('Mark 2');
$debLog->error('Mark 3');

// Should be swallowed
$othLog->info('booting up');
// Should be logged to stderr
$othLog->error('missing dependencies');