<?php

/**
 * /src/ThinFrame/Twig/View.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig;

use ThinFrame\Foundation\Constant\DataType;
use ThinFrame\Foundation\Helper\TypeCheck;
use ThinFrame\Karma\Controller\ViewInterface;


/**
 * Class View
 *
 * @package ThinFrame\Twig
 */
class View implements ViewInterface
{
    /**
     * @var string
     */
    private $viewIdentifier;
    /**
     * @var array
     */
    private $variables;

    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * Constructor
     *
     * @param string $identifier
     * @param array  $variables
     */
    public function __construct($identifier, array $variables = [])
    {
        TypeCheck::doCheck(DataType::STRING);
        $this->viewIdentifier = $identifier;
        $this->variables      = $variables;
    }

    /**
     * Get view identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->viewIdentifier;
    }

    /**
     * Get variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param \Twig_Environment $twigEnvironment
     */
    public function setTwigEnvironment(\Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->twigEnvironment->render($this->viewIdentifier, $this->variables);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
