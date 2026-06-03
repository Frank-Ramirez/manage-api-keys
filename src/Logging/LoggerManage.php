<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerManage {

    public function Logger(string $type, string $msg) {

        $loggerType = [ 
            'info' => Logger::INFO, 
            'warning' => Logger::WARNING, 
            'error' => Logger::ERROR, 
            'debug' => Logger::DEBUG, 
            'critical' => Logger::CRITICAL];

        $level = $loggerType[ trim($type) ] ?? Logger::INFO;

        try {
            $logger = new Logger('system');
            $logger->pushHandler(new StreamHandler( __DIR__ .'/../../log/system.log', Logger::DEBUG));
            $logger->log($level, $msg);
        } catch (\Throwable $t) {
            error_log("Error log - apikeys: " . $t->getMessage() . "\n StackTrace: " . $t->getTraceAsString());
        }
    }
}