<?php

namespace Fapi\Component\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Ucc\Fundation\Config;
use \ReflectionObject;

/**
 * Fapi\Component\HttpKernel\Kernel
 *
 * The Kernel is the heart of the Fapi system.
 * It turns Request into Response object.
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
abstract class Kernel
{
    protected $config;
    protected $startTime;
    protected $rootDir;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->startTime    = microtime(true);
        $this->rootDir      = $this->getRootDir();
        $this->config       = new Config();
    }

    /**
     * Boot method. Starts all processes.
     */
    public function run()
    {
        $request = Request::createFromGlobals();

        return $this->handle($request);
    }

    /**
     * Handles request.
     *
     * @param   Request     $request
     */
    public function handle(Request $request)
    {
    }

    /**
     * Gets the request start time.
     *
     * @return int The request start timestamp
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Gets root directory.
     *
     * @return string
     */
    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $reflection     = new ReflectionObject($this);
            $this->rootDir  = str_replace('\\', '/', dirname($reflection->getFileName()));
        }

        return $this->rootDir;
    }

    /**
     * Gets config.
     *
     * @return ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }
}
