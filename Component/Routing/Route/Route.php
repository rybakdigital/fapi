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

    public function __construct($path = null, $methods = array(), $controller = null, $calls = null, $requirements = array(), $regex = null)
    {
        $this
            ->setPath($path)
            ->setMethods($methods)
            ->setController($controller)
            ->setCalls($calls)
            ->setRequirements($requirements)
            ->setRegex($regex);
    }

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
        // Capitalise method
        $method = strtoupper($method);

        if (!in_array($method, self::$availableMethods)) {
            throw new InvalidArgumentException(sprintf('Invalid method for route with path "%s". Method must be one of: ' . implode(', ', self::$availableMethods) . '. Got "%s" instead.', $this->getPath(), $method));
        }

        if (!in_array($method, $this->methods)) {
            $this->methods[] = $method;
        }

        // Add HEAD method if GET has been allowed for this Route
        if ($method == 'GET') {
            $this->addMethod('HEAD');
        }

        return $this;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        if (empty($controller)) {
            throw new InvalidArgumentException(sprintf('Missing controller for route with path "%s".', $this->getPath()));
        }

        $this->controller = $controller;

        return $this;
    }

    public function getCalls()
    {
        return $this->calls;
    }

    public function setCalls($callable)
    {
        if (empty($callable)) {
            throw new InvalidArgumentException(sprintf('Missing "calls" argument for route with path "%s".', $this->getPath()));
        }

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
