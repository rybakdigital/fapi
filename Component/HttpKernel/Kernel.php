<?php

namespace Fapi\Component\HttpKernel;

/**
 * Fapi\Component\HttpKernel
 *
 * The Kernel is the heart of the Fapi system.
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
abstract class Kernel
{
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
