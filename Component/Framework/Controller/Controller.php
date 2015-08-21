<?php

namespace Fapi\Component\Framework\Controller;

use Ucc\Data\Validator\Validator;
use Ucc\Data\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function __construct($request = null)
    {
        $this->setRequest($request);
        $this->setValidator(new Validator());
        $this->passRequestData();
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
}
