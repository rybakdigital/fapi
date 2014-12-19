<?php

namespace Fapi\Component\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Ucc\Config\Config;
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
        $this->loadConfiguration();
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

    /**
     * Loads app Configuration.
     *
     * @return ConfigInterface
     */
    public function loadConfig($fileName)
    {
        // Check if the given file exists
        if (file_exists($fileName)) {
            $file = file_get_contents($fileName);
        } else {
            // Soo the file can not be located
            // Check in config folder
            if (file_exists($this->getRootDir() . '/config/' . $fileName)) {
                $file = file_get_contents($this->getRootDir() . '/config/' . $fileName);
            } else {
                throw new \InvalidArgumentException(sprintf('The file "%s" does not exist.', $fileName));
            }
        }

        $array  = Yaml::parse($file);

        // Discover configuration
        foreach ($array as $key => $params) {
            // Import Resources
            if ($key == 'imports') {
                foreach ($params as $resource) {
                    foreach ($resource as $key => $resourceName) {
                        if ($key == 'resource') {
                            $this->loadConfig($resourceName);
                        }
                    }
                }
            // Save parameters in the Config
            } elseif ($key == 'parameters') {
                foreach ($params as $key => $param) {
                    $this->config->setParameter($key, $param);
                }
            }
        }

        return $this->config;
    }
}
