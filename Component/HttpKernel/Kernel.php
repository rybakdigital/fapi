<?php

namespace Fapi\Component\HttpKernel;

use Symfony\Component\HttpFoundation\Request;

/**
 * Fapi\Component\HttpKernel
 *
 * The Kernel is the heart of the Fapi system.
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
abstract class Kernel
{
    protected $startTime;

    protected $container;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    /**
     * Gets container.
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Boot method. Starts all processes.
     */
    public function run()
    {
        $request = Request::createFromGlobals();
        $this->handle($request);
    }

    /**
     * Handles request.
     *
     * @param   Request     $request
     */
    public function handle(Request $request)
    {

    }
}
