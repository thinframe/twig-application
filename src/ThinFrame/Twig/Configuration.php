<?php

namespace ThinFrame\Twig;

use ThinFrame\Applications\DependencyInjection\Extensions\ConfigurationAwareInterface;

/**
 * Class Configuration
 *
 * @package ThinFrame\Twig
 * @since   0.2
 */
class Configuration implements ConfigurationAwareInterface
{
    /**
     * @var array
     */
    private $configuration = [];

    /**
     * @param array $configuration
     *
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

}