<?php

/**
 * /src/ThinFrame/Twig/TwigApplication.php
 *
 * @author Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig;

use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;
use ThinFrame\Applications\DependencyInjection\Extensions\ConfigurationManager;


/**
 * Class TwigApplication
 *
 * @package ThinFrame\Twig
 * @since   0.1
 */
class TwigApplication extends AbstractApplication
{
    /**
     * initialize configurator
     *
     * @param ContainerConfigurator $configurator
     *
     * @return mixed
     */
    public function initializeConfigurator(ContainerConfigurator $configurator)
    {
        $configurator->addConfigurationManager(
            new ConfigurationManager('twig', 'thinframe.twig.configuration')
        );
    }

    /**
     * Get configuration files
     *
     * @return mixed
     */
    public function getConfigurationFiles()
    {
        return [
            'resources/services.yml',
            'resources/config.yml'
        ];
    }

    /**
     * Get application name
     *
     * @return string
     */
    public function getApplicationName()
    {
        return 'TwigApplication';
    }

    /**
     * Get parent applications
     *
     * @return AbstractApplication[]
     */
    protected function getParentApplications()
    {
        return [];
    }
}
