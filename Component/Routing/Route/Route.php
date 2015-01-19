<?php

namespace Fapi\Component\Routing\Route;

use \InvalidArgumentException;

/**
 * Fapi\Component\Routing\Route\Route
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
class Route
{
    /**
     * List of HTTP methods accepted by Route
     */
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

    /**
     * Gets Path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets Path
     *
     * @param   string  $pattern
     * @return  Route
     */
    public function setPath($pattern)
    {
        // A pattern must start with a slash and must not have multiple slashes at the beginning because the
        // generated path for this route would be confused with a network path, e.g. '//domain.com/path'.
        $this->path = '/' . ltrim(trim($pattern), '/');

        return $this;
    }

    /**
     * Gets methods
     *
     * @return  array       Array of methods accepted by Route
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Sets methods
     *
     * @param   array       $methods    Array of methods to set for the Route
     * @return  array       Array of methods accepted by Route
     */
    public function setMethods(array $methods)
    {
        foreach ($methods as $method) {
            $this->addMethod($method);
        }

        return $this;
    }

    /**
     * Adds method to array of methods accepted by Route
     *
     * @param   string  $method     Name of HTTP method to add
     * @return  Route
     * @throws  InvalidArgumentException
     */
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

    /**
     * Gets Contorller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets Contorller
     *
     * @param   string  $controller     Name of the controller to call by Route
     * @return  Route
     * @throws  InvalidArgumentException
     */
    public function setController($controller)
    {
        if (empty($controller)) {
            throw new InvalidArgumentException(sprintf('Missing controller for route with path "%s".', $this->getPath()));
        }

        $this->controller = $controller;

        return $this;
    }

    /**
     * Gets calls
     *
     * @return string
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * Sets calls
     *
     * @param   string  $callable   Name of the method to call by Route after resolving Controller
     * @return  Route
     * @throws  InvalidArgumentException
     */
    public function setCalls($callable)
    {
        if (empty($callable)) {
            throw new InvalidArgumentException(sprintf('Missing "calls" argument for route with path "%s".', $this->getPath()));
        }

        $this->calls = $callable;

        return $this;
    }

    /**
     * Gets requirements
     *
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * Sets requirements
     *
     * @param   array   requirements    Array of requirements for Route
     * @return  Route
     */
    public function setRequirements(array $requirements)
    {
        foreach ($requirements as $requirement => $type) {
            $this->addRequirement($requirement, $type);
        }

        return $this;
    }

    /**
     * Adds requirement
     *
     * @param   string      $requirement        Requirements for Route
     * @param   string      $type               Type of requirement
     * @return  Route
     */
    public function addRequirement($requirement, $type)
    {
        $this->requirements[$requirement] = strtolower($type);

        return $this;
    }

    /**
     * Gets regex
     *
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Sets regex
     *
     * @param   string  $pattern    Regular Expression pattern of the Route
     * @return  Route
     */
    public function setRegex($pattern)
    {
        $this->regex = $pattern;

        return $this;
    }
}
