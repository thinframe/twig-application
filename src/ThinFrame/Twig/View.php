<?php

namespace ThinFrame\Twig;

use ThinFrame\Foundation\Constants\DataType;
use ThinFrame\Foundation\Helpers\TypeCheck;

/**
 * Class View
 *
 * @package ThinFrame\Twig
 * @since   0.1
 */
class View
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
}
