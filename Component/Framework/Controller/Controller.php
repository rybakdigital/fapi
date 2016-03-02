<?php

namespace Fapi\Component\Framework\Controller;

use Ucc\Data\Validator\Validator;
use Ucc\Data\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ucc\Config\Config;
use Ucc\Data\Format\Format\Format;

/**
 * Fapi\Component\Framework\Controller\Controller
 *
 * This is base controller. Provides foundations
 * and methods for all controllers inside the framework.
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
class Controller
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Config $config = null, $request = null)
    {
        if (is_null($request)) {
            $request = Request::createFromGlobals();
        }
        if (is_null($config)) {
            $config = new Config;
        }

        $this->config = $config;
        $this->setRequest($request);
        $this->setValidator(new Validator());
        $this->setBaseRequestFormat();
        $this->setDefaultChecks();
        $this->passRequestData();
        $this->response = new Response;
    }

    /**
     * Gets Validator
     *
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Sets Validator
     *
     * @param   ValidatorInterface    $validator
     * @return  Controller
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Gets request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets request
     *
     * @param   Request    $request
     * @return  Controller
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Gets response
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets response
     *
     * @param   Response    $response
     * @return  Controller
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Gets config
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets config
     *
     * @param   Config    $config
     * @return  Controller
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Helper method. Passes request data.
     *
     * @return  Controller
     */
    public function passRequestData()
    {
        $this
            ->getValidator()
                ->setInputData($this
                    ->getRequest()
                        ->query
                            ->all()
                );

        $this
            ->getValidator()
                ->setInputData($this
                    ->getRequest()
                        ->request
                            ->all()
                );

        return $this;
    }

    /**
     * Gets repository by name
     *
     * @param   string      $name       Name of the repository to get
     */
    public function getRepository($name = null)
    {
        // Resolve repository class name
        $repositoryName = $this->resolveRepositoryClassName($name);

        return new $repositoryName($this->getValidator(), $this->config);
    }

    public function resolveRepositoryClassName($name)
    {
        // Check default class name
        if (is_null($name)) {
            // First namespace by removing class name from full class name
            $parts = explode('\\', get_class($this));

            // Build base namespace
            $namespace = substr(get_class($this), 0 , (strripos(get_class($this), end($parts))));

            // Add 'Entity\' followed by $className + Repository
            $namespace = $namespace . 'Entity\\' . end($parts) . 'Repository';
        } elseif (is_string($name)) {
            // Name supplied, let's check if it's a class name or reference:
            // Class name: 'v1\Products\Entity\ProductsRepository'
            // Reference: 'v1:Products:ProductsRepository'
            $parts = explode(':', $name);
            // Reference should be build of exactly 3 elements, let's check
            if (count($parts) === 3) {
                $namespace = self::getRepositoryClassnameByReference($name);
            } else {
                // So its not a reference, let's try fully qualified class name
                $namespace = $name;
            }
        }

        if (class_exists($namespace)) {
            return $namespace;
        }

        throw new \Exception("Could not find repository: ". $name);
    }

    /**
     * Gets Repository class name by Reference
     *
     * @param   string  $reference      Reference to Repository class.
     *                                  This should be in a form of {version}:{controller}:{repositoryName}
     *                                  For example: v1:Products:MyRepository
     *                                  Will resolve to: 'v1\Products\Entity\MyRepository'
     * @return  string
     */
    public static function getRepositoryClassnameByReference($reference)
    {
        $parts = explode(':', $reference);

        // Check name is string
        if (!is_string($reference) || count($parts) !== 3) {
            throw new \Exception("Invalid Repository Class Reference. Repository reference should consist of 3 parts separated by colon, for example v1:Products:MyRepository");
        }

        return $parts[0] . '\\' . $parts[1] . '\\' . 'Entity\\' . $parts[2];
    }

    public function setDefaultChecks()
    {
        // Get Validator
        $validator = $this->getValidator();

        $defaultChecks = array(
            'format'     => array(
                'opt'       => true,
                'type'      => 'format',
                'default'   => Format::FORMAT_JSON,
            ),
            'display'    => array(
                'opt'       => true,
                'type'      => 'display',
            ),
        );


        if (is_a($validator, 'Ucc\Data\Validator\ValidatorInterface')) {
            $validator->setChecks($defaultChecks);
        }
    }

    public function setBaseRequestFormat()
    {
        // Check headers for Accept
        $acceptHeaders = $this->request->getAcceptableContentTypes();

        foreach ($acceptHeaders as $header) {
            $baseFormt = $this->request->getFormat($header);

            if ($baseFormt == Format::FORMAT_JSON) {
                $this->request->setRequestFormat($baseFormt);
            }

            // Override base HTML format with JSON (default)
            if ($baseFormt == 'html') {
                $this->request->setRequestFormat(Format::FORMAT_JSON);
            }
        }
    }
}
