<?php

namespace Fapi\Component\Routing\Route;

use \InvalidArgumentException;

/**
 * Fapi\Component\Routing\Route\Route
 */
class Route
{
    public static $availableMethods = array('HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'PURGE', 'OPTIONS', 'TRACE', 'CONNECT');

    /**
     * Path of the Route
     *
     * @var string
     */
    private $path = '/';

    /**
     * Array of HTTP methods served by Route
     *
     * @var array
     */
    private $methods = array();

    /**
     * Controller served by Route
     *
     * @var string
     */
    private $controller;

    /**
     * Method to be called by conroller
     *
     * @var string
     */
    private $calls;

    /**
     * Array of Route requirements
     *
     * @var array
     */
    private $requirements = array();

    /**
     * @var string
     */
    private $regex;

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($pattern)
    {
        // A pattern must start with a slash and must not have multiple slashes at the beginning because the
        // generated path for this route would be confused with a network path, e.g. '//domain.com/path'.
        $this->path = '/' . ltrim(trim($pattern), '/');

        return $this;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setMethods(array $methods)
    {
        foreach ($methods as $method) {
            $this->addMethod($method);
        }

        return $this;
    }

    public function addMethod($method)
    {
        if (!in_array($method, $this->methods)) {
            $this->methods[] = strtoupper($method);
        }

        return $this;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    public function getCalls()
    {
        return $this->calls;
    }

    public function setCalls($callable)
    {
        $this->calls = $callable;

        return $this;
    }

    public function getRequirements()
    {
        return $this->requirements;
    }

    public function setRequirements(array $requirements)
    {
        foreach ($requirements as $requirement => $type) {
            $this->addRequirement($requirement, $type);
        }

        return $this;
    }

    public function addRequirement($requirement, $type)
    {
        $this->requirements[$requirement] = strtolower($type);

        return $this;
    }

    public function getRegex()
    {
        return $this->regex;
    }

    public function setRegex($pattern)
    {
        $this->regex = $pattern;

        return $this;
    }
}
