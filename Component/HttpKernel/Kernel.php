<?php

namespace Fapi\Component\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
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
    protected $container;
    protected $startTime;
    protected $rootDir;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->startTime    = microtime(true);
        $this->rootDir      = $this->getRootDir();
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
            $r = new ReflectionObject($this);
            $this->rootDir = str_replace('\\', '/', dirname($r->getFileName()));
        }

        return $this->rootDir;
    }

    /**
     * Gets container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
