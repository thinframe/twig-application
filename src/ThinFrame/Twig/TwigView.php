<?php

/**
 * /src/ThinFrame/Twig/View.php
 *
 * @author Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig;

use ThinFrame\Foundation\Constants\DataType;
use ThinFrame\Foundation\Helpers\TypeCheck;
use ThinFrame\Karma\ViewController\View;

/**
 * Class View
 *
 * @package ThinFrame\Twig
 * @since   0.1
 */
class TwigView extends View
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
    private $twig;

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
     * @param \Twig_Environment $twig
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->twig->render($this->viewIdentifier, $this->variables);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
