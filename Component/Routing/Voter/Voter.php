<?php

namespace Fapi\Component\Routing\Voter;

use Symfony\Component\HttpFoundation\Request;
use Fapi\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Fapi\Component\Routing\Voter\Voter
 *
 * Votes witch Route candidate suits best for a given Request
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
class Voter
{
    /**
     * @var array
     */
    private $candidates = array();

    /**
     * @var Request
     */
    private $request;

    /**
     * @var integer
     */
    private $priority = 0;

    /**
     * @var array
     */
    private $prioritised = array();

    /**
     * Votes on candidates and returns Route that matches the path closest
     *
     * @param   array       $candidates
     * @param   Request     $request
     * @return  Route
     * @throws  ResourceNotFoundException
     */
    public function vote($candidates, Request $request)
    {
        $this->candidates   = $candidates;
        $this->request      = $request;

        // Prioritise candidates
        $this->prioritseCandidates();

        for ($i=$this->priority; $i >= 0 ; $i--) { 
            if (isset($this->prioritised[$i]) && !empty($this->prioritised[$i])) {
                return $this->prioritised[$i][0];
            }
        }

        throw new ResourceNotFoundException(sprintf('No routes found for "%s".', $this->request->getPathInfo()));
    }

    /**
     * Prioritises candidates
     *
     * @return void()
     */
    protected function prioritseCandidates()
    {
        $requestParts       = array();

        // Turn numeric request parts into integers
        foreach (explode('/', $this->request->getPathInfo()) as $part) {
            if (is_numeric($part)) {
                $requestParts[] = (int) $part;
            } else {
                $requestParts[] = $part;
            }
        }

        foreach ($this->candidates as $key => $route) {
            $priority = 0;
            $this->establishPriority(count($route->getRequirements()));

            // Remove from candidates list those Routes that Method does not match Request method
            if (!in_array($this->request->getMethod(), $route->getMethods())) {
                unset($this->candidates[$key]);
            }

            $routeParts = explode('/', $route->getPath());

            foreach ($route->getRequirements() as $requirement => $type) {
                $pattern = '{' . $requirement . '}';
                $key = array_search($pattern, $routeParts);
                $part = $routeParts[$key];

                if (preg_match('/^\{\w+\}/', $part)) {
                    if ($type == 'int') {
                        if (is_numeric($requestParts[$key])) {
                            $priority ++;
                        }
                    } elseif ($type == 'str') {
                        if (is_string($requestParts[$key])) {
                            $priority ++;
                        }
                    }
                }

            }

            $this->prioritised[$priority][] = $route;
        }
    }

    /**
     * Establishes priority of the Voter based on number of parameters
     *
     * @param   integer     $numberOfRequirements
     * @return  Voter
     */
    protected function establishPriority($numberOfRequirements)
    {
        if ($this->priority < $numberOfRequirements) {
            $this->priority = $numberOfRequirements;
        }

        return $this;
    }
}
