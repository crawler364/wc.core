<?php

namespace WC\Core\Helpers;

use Bitrix\Main\Web\Json;

class Logger
{
    public const FILE_NAME_DEFAULT = 'default';
    protected static $classLoggers = [];
    private $logFile;

    public function __construct($logName = null)
    {
        $logName = $logName ?: self::FILE_NAME_DEFAULT;
        $rootLogPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/log/';
        $logFilePath = $rootLogPath . $logName . '.log';
        $logDirPath = dirname($logFilePath);

        if (!is_dir($logDirPath) && !mkdir($logDirPath, 0777, true) && !is_dir($logDirPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $logDirPath));
        }

        $this->logFile = fopen($logFilePath, 'ab');
    }

    public function info($message, $data = null)
    {
        return $this->log('[INFO] ' . $message, $data);
    }

    public function error($message, $data = null)
    {
        return $this->log('[ERROR] ' . $message, $data);
    }

    private function log($message, $data = null)
    {
        if (!empty($data)) {
            $message .= ' ' . Json::encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        }

        $message = '[' . date('Y-m-d H:i:s') . '] ' . $message;

        return $this->writeMessage2File($message);
    }

    private function writeMessage2File(string $message)
    {
        return fwrite($this->logFile, PHP_EOL . $message);
    }

    public static function initByMethodName($calledMethod)
    {
        $logFileName = str_replace(['\\', '::'], '/', $calledMethod);

        return new static($logFileName);
    }

    public static function getByClassName($className)
    {
        if (!isset(static::$classLoggers[$className])) {
            $logFileName = str_replace('\\', '/', $className);
            static::$classLoggers[$className] = new static($logFileName);
        }

        return static::$classLoggers[$className];
    }
}
