<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Twig;

use PhpCollection\Map;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;
use ThinFrame\Twig\DependencyInjection\HybridExtension;


/**
 * Class TwigApplication
 *
 * @package ThinFrame\Twig
 * @since   0.1
 */
class TwigApplication extends AbstractApplication
{
    /**
     * Get application name
     *
     * @return string
     */
    public function getName()
    {
        return 'TwigApplication';
    }

    /**
     * Get application parents
     *
     * @return AbstractApplication[]
     */
    public function getParents()
    {
        return [];
    }

    /**
     * Set different options for the container configurator
     *
     * @param ContainerConfigurator $configurator
     */
    protected function setConfiguration(ContainerConfigurator $configurator)
    {
        $configurator
            ->addResources(
                [
                    'Resources/config/services.yml',
                    'Resources/config/listeners.yml',
                ]
            )
            ->addExtension($hybridExtension = new HybridExtension())
            ->addCompilerPass($hybridExtension);

    }

    /**
     * Set application metadata
     *
     * @param Map $metadata
     *
     */
    protected function setMetadata(Map $metadata)
    {

    }
}
