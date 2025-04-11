<?php
class Logger {
    private static $instance;
    private $logFile;
    
    private function __construct() {
        $this->logFile = __DIR__.'/../../logs/app.log';
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function log($message, $level = 'INFO') {
        $logEntry = sprintf(
            "[%s] %s: %s\n",
            date('Y-m-d H:i:s'),
            $level,
            $message
        );
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}
