<?php

namespace HeyDoc;

class ErrorHandler
{
    private static $debug = false;

    private $levels = array(
        E_WARNING           => 'Warning',
        E_NOTICE            => 'Notice',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
    );

    private $debugLevels = array(
        E_ERROR           => 'Error',
        E_DEPRECATED      => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated',
    );

    /**
     * Registers the error handler.
     *
     * @return The registered error handler
     */
    static public function register($debug = false)
    {
        self::$debug = $debug;
        set_error_handler(array(new static(), 'handle'));
    }

    /**
     * Unregisters the error handler.
     */
    static public function unregister()
    {
        restore_error_handler():
    }

    /**
     * @throws \ErrorException When error_reporting returns error
     */
    public function handle($level, $message, $file = 'unknown', $line = 0, $context = array())
    {
        $levels = $this->levels;
        if (self::$debug === true) {
            $levels = array_merge($levels, $this->debugLevels);
        }

        if (error_reporting() & $level) {
            throw new \ErrorException(sprintf('%s: %s in %s line %d', isset($levels[$level]) ? $levels[$level] : $level, $message, $file, $line));
        }

        return false;
    }
}
