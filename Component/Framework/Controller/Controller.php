<?php

namespace Fapi\Component\Framework\Controller;

use Ucc\Data\Validator\Validator;
use Ucc\Data\Validator\ValidatorInterface;

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

    public function __construct()
    {
        $this->setValidator(new Validator());
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
}
